<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'referral' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'alpha_num', 'min:6', 'max:10', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:15'],
            'address' => ['required', 'string', 'max:255'],
            'ktp' => ['required', 'string', 'size:16'],
            'foto_ktp' => ['required', 'mimes:jpeg,png,jpg', 'max:2048'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $parent_id = NULL;
        if(@$data['referral']){
            $ref = User::select('id')->where(['referral_code'=>$data['referral'],'is_referral'=>1])->first();
            if($ref){
                $parent_id = $ref->id;
            }
        }
        $file = $data['foto_ktp'];
        $filename = uniqid().'.'.$file->getClientOriginalExtension();
        $file->move('file/ktp/',$filename);
        $phone_number = $data['phone_number'];
        $phone_number = preg_replace('/[62 | +62]/','',$phone_number);
        $phone_number = ltrim($phone_number, '0');
        $user = User::create([
            'parent_id' => $parent_id, 
            'name' => $data['name'],
            'username' => strtolower($data['username']),
            'email' => $data['email'],
            'phone_number' => $phone_number,
            'address' => $data['address'],
            'ktp' => $data['ktp'],
            'file_ktp' => $filename,
            'password' => Hash::make($data['password']),
        ]);
        $user->attachRole('member');
        return $user;
    }

    public function referal(Request $request,$code)
    {
        $user = User::select('id')->where(['referral_code'=>$code,'is_referral'=>1])->first();
        if($user){
            $request->session()->put('referral', $code);
            return redirect()->route('register');
        }else{
            $request->session()->flash('failed', 'Referal tidak ditemukan.');
            return redirect()->route('register');
        }
    }
}
