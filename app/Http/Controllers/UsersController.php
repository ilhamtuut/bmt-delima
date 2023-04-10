<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Bank;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $banks = Bank::get();
        $roles = Role::select('display_name','id')->orderBy('name')->get();
        return view('pages.users.index',compact('roles','banks'));
    }

    public function list(Request $request,$role_name)
    {
        $search = $request->search;
        $status = $request->status;
        $data = User::whereHas('roles', function ($query) use($role_name) {
                    $query->where('roles.name', $role_name);
                })
                ->whereNotNull('email_verified_at')
                ->when($search, function ($cari) use ($search) {
                    return $cari->where('username', 'LIKE' ,$search.'%')
                    ->orWhere('account_number', 'LIKE', $search.'%')
                    ->orWhere('name', 'LIKE', $search.'%')
                    ->orWhere('email', 'LIKE', $search.'%');
                })
                ->when($status, function ($cari) use ($status) {
                    return $cari->where('status', $status);
                })->paginate(20);
        $role = $role_name;
        $request->session()->put('roles', $role);
        return view('pages.users.list', compact('data', 'role'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'alpha_num', 'min:6', 'max:10', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:15'],
            'address' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string'],
            'bank_code' => ['nullable', 'string'],
            'bank_name' => ['nullable', 'string'],
            'account_name' => ['nullable', 'string'],
            'account_number' => ['nullable', 'string'],
            'ktp' => ['required', 'string', 'size:16'],
            'foto_ktp' => ['required', 'mimes:jpeg,png,jpg', 'max:2048'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        $data = $request->all();
        $file = $data['foto_ktp'];
        $filename = uniqid().'.'.$file->getClientOriginalExtension();
        $file->move('file/ktp/',$filename);
        $phone_number = $data['phone_number'];
        $phone_number = preg_replace('/[62 | +62]/','',$phone_number);
        $phone_number = ltrim($phone_number, '0');
        $user = User::create([
            'name' => $data['name'],
            'username' => strtolower($data['username']),
            'email' => $data['email'],
            'phone_number' => $phone_number,
            'address' => $data['address'],
            'bank_code' => $data['bank_code'],
            'bank_name' => $data['bank_name'],
            'bank_account_name' => $data['account_name'],
            'bank_account_number' => $data['account_number'],
            'ktp' => $data['ktp'],
            'file_ktp' => $filename,
            'password' => Hash::make($data['password']),
            'email_verified_at' => now()
        ]);
        $user->roles()->attach($request->role);
        $request->session()->flash('success', 'Successfully, create user');
        return redirect()->back();
    }

    public function edit(Request $request, $id)
    {
        $banks = Bank::get();
        $user = User::find($id);
        $role_user = $request->session()->get('roles');
        $roles = Role::select('display_name', 'id')->get();
        return view('pages.users.edit', compact('user', 'roles', 'role_user','banks'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'alpha_num', 'min:6', 'max:10', 'unique:users,username,'.$id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            'phone_number' => ['required', 'string', 'max:15'],
            'address' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string'],
            'bank_code' => ['nullable', 'string'],
            'bank_name' => ['nullable', 'string'],
            'account_name' => ['nullable', 'string'],
            'account_number' => ['nullable', 'string'],
            'ktp' => ['required', 'string', 'size:16'],
            'foto_ktp' => ['nullable', 'mimes:jpeg,png,jpg', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user = User::find($id);
        $data = $request->all();
        $phone_number = $data['phone_number'];
        $phone_number = preg_replace('/[62 | +62]/','',$phone_number);
        $phone_number = ltrim($phone_number, '0');
        $dataUpdate = [
            'name' => $data['name'],
            'username' => strtolower($data['username']),
            'email' => $data['email'],
            'phone_number' => $phone_number,
            'address' => $data['address'],
            'bank_code' => $data['bank_code'],
            'bank_name' => $data['bank_name'],
            'bank_account_name' => $data['account_name'],
            'bank_account_number' => $data['account_number'],
            'ktp' => $data['ktp']
        ];
        if(@$data['foto_ktp']){
            if($user->file_ktp){
                unlink('file/ktp/'.$user->file_ktp);
            }
            $file = $data['foto_ktp'];
            $filename = uniqid().'.'.$file->getClientOriginalExtension();
            $file->move('file/ktp/',$filename);
            $dataUpdate['file_ktp'] = $filename;
        }
        if(@$data['password']){
            $dataUpdate['password'] = Hash::make($data['password']);
        }
        $user->update($dataUpdate);

        $user->roles()->sync($request->role);
        $request->session()->flash('success', 'Successfully updated user '.$user->username);
        return redirect()->to('users/list/'.$request->session()->get('roles'));
    }

    public function block(Request $request,$id)
    {
        $user = User::find($id);
        $status = $user->status;
        if($status == 2){
            $block = 1;
            $msg = 'Activated username '.$user->username;
        }else{
            $block = 2;
            $msg = 'Suspended username '.$user->username;
        }
        $user->status = $block;
        $user->save();

        $request->session()->flash('success', 'Successfully, '.$msg);
        return redirect()->back();
    }
}
