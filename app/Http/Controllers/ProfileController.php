<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
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
        return view('pages.profile.index');
    }
    
    public function settings(Request $request)
    {
        return view('pages.profile.settings');
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8|same:new_password',
        ]);

        $users = Auth::user();
        $password = $request->current_password;
        $hasPassword = Hash::check($password,$users->password);
        if ($hasPassword){
            $users->fill([
                'password' => Hash::make($request->new_password)
            ]);
            $users->save();

            $request->session()->flash('success', 'Berhasil memperbaharui password');
            return redirect()->back();
        }else {
            $request->session()->flash('failed', 'Password salah');
            return redirect()->back();
        }
    }
    
    public function bank(Request $request)
    {
        if(Auth::user()->bank_account_name){
            return redirect()->route('profile.index');
        }
        $banks = Bank::get();
        return view('pages.profile.bank',compact('banks'));
    }

    public function updateBank(Request $request)
    {
        $this->validate($request, [
            'bank_code' => 'required|string',
            'bank_name' => 'required|string',
            'account_name' => 'required|string',
            'account_number' => 'required|string',
        ]);

        $users = Auth::user();
        $users->fill([
            'bank_code' => $request->bank_code,
            'bank_name' => $request->bank_name,
            'bank_account_name' => $request->account_name,
            'bank_account_number' => $request->account_number,
        ]);
        $users->save();

        $request->session()->flash('success', 'Berhasil menambahkan akun bank');
        return redirect()->route('profile.index');
    }

    public function uploadFoto(Request $request)
    {
        $this->validate($request, [
            'foto' => ['required', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $file = $request->file('foto');
        $filename = uniqid().'.'.$file->getClientOriginalExtension();
        $file->move('file/profile/',$filename);

        $users = Auth::user();
        if($users->foto_profile){
            unlink('file/profile/'.$users->foto_profile);
        }
        $users->fill([
            'foto_profile' => $filename,
        ]);
        $users->save();

        $request->session()->flash('success', 'Berhasil mengubah foto profil');
        return redirect()->route('profile.index');
    }
}
