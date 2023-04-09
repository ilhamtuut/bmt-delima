<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();
        $user['balance'] = $user->saldo();
        return $user;
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8|same:new_password',
        ]);

        $user = $request->user();
        $password = $request->current_password;
        $hasPassword = Hash::check($password,$user->password);
        if ($hasPassword){
            $user->fill([
                'password' => Hash::make($request->new_password)
            ]);
            $user->save();

            $res = [
                'success' => true,
                'message' => 'Berhasil memperbaharui password.'
            ];
            return response($res, 200);
        }else {
            $res = [
                'success' => false,
                'message' => 'Password salah.'
            ];
            return response($res, 422);
        }
    }

    public function updateBank(Request $request)
    {
        $this->validate($request, [
            'bank_code' => 'required|string',
            'bank_name' => 'required|string',
            'account_name' => 'required|string',
            'account_number' => 'required|string',
        ]);

        $user = $request->user();
        $user->fill([
            'bank_code' => $request->bank_code,
            'bank_name' => $request->bank_name,
            'bank_account_name' => $request->account_name,
            'bank_account_number' => $request->account_number,
        ]);
        $user->save();

        $res = [
            'success' => true,
            'message' => 'Berhasil menambahkan akun bank.'
        ];
        return response($res, 200);
    }
}
