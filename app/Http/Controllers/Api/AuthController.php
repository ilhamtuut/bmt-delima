<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Rules\Base64Rule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class AuthController extends Controller
{
    use SendsPasswordResetEmails;

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = User::where(['email'=> $data['email'], 'status' => 1])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'success' => false,
                'message' => 'incorrect email or password'
            ], 422);
        }

        if(!$user->hasVerifiedEmail()){
            return response([
                'success' => false,
                'message' => 'Akun anda belum terverifikasi'
            ], 422);
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'success' => true,
            'token' => $token,
            'data' => $user
        ];

        return response($res, 200);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'alpha_dash', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'ktp' => ['required', 'string', 'size:16'],
            'foto_ktp' => ['required', new Base64Rule()],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $phone_number = $data['phone_number'];
        $phone_number = preg_replace('/[62 | +62]/','',$phone_number);
        $phone_number = ltrim($phone_number, '0');

        $image = $request->foto_ktp;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = uniqid().'.'.'png';
        \File::put('file/ktp/' . $imageName, base64_decode($image));

        $user = User::create([
            'name' => $data['name'],
            'username' => strtolower($data['username']),
            'email' => $data['email'],
            'phone_number' => $phone_number,
            'address' => $data['address'],
            'ktp' => $data['ktp'],
            'file_ktp' => $imageName,
            'password' => Hash::make($data['password']),
        ]);
        $user->attachRole('member');
        
        event(new Registered($user));

        $res = [
            'success' => true,
            'message' => 'Successfully created an account, please click the email verification link that we have sent.'
        ];
        return response($res, 200);
    }

    public function resendEmailVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            $res = [
                'success' => true,
                'message' => 'Your account has verified'
            ];
            return response($res, 200);
        }

        $request->user()->sendEmailVerificationNotification();
        $res = [
            'success' => true,
            'message' => 'Verification link sent!'
        ];
        return response($res, 200);
    }

    public function sendResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email'
        ]);

        $this->sendResetLinkEmail($request);
        
        $res = [
            'success' => true,
            'message' => 'We have emailed your password reset link!'
        ];
        return response($res, 200);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        $res = [
            'success' => true,
            'message' => 'user logged out'
        ];
        return response($res, 200);
    }
}
