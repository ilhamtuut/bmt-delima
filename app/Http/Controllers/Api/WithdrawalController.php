<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Setting;
use App\Models\Mutation;
use App\Models\Deposito;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WithdrawalController extends Controller
{
    public function send(Request $request)
    {
        $this->validate($request, [
            'nominal' => 'required|numeric'
        ]);
        $min = Setting::where('name','Minimal Withdrawal')->first()->value;
        if($request->nominal < $min){
            return response()->json([
                'success' => true,
                'message' => 'Maksimal penarikan Rp'.number_format($max, 0, ',', '.')
            ],422);
        }
        $max = Setting::where('name','Maximum Withdrawal')->first()->value;
        if($request->nominal > $max){
            return response()->json([
                'success' => true,
                'message' => 'Maksimal penarikan Rp'.number_format($max, 0, ',', '.')
            ],422);
        }
        $user = $request->user();
        if(!$user->bank_name){
            return response()->json([
                'success' => true,
                'message' => 'Mohon isi akun bank terlebih dahulu untuk dapat melakukan penarikan'
            ],422);
        }
        // check balance
        if($user->balance() < $request->nominal){
            return response()->json([
                'success' => true,
                'message' => 'Saldo Anda tidak cukup atau belum bisa melakukan penarikan'
            ],422);
        }
        $withdrawal = Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $request->nominal,
            'bank_code' => $user->bank_code,
            'bank_name' => $user->bank_name,
            'bank_account_name' => $user->bank_account_name,
            'bank_account_number' => $user->bank_account_number,
        ]);

        Mutation::create([
            'trxid' => $withdrawal->trxid,
            'user_id' => $withdrawal->user_id,
            'note' => 'Penarikan Rp'.number_format($withdrawal->amount,0,',','.'),
            'amount' => $withdrawal->amount,
            'debit' => 0,
            'kredit' => $withdrawal->amount,
            'reference_id' => $withdrawal->id,
            'reference_type' => Withdrawal::class,
            'status' => 1
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil melakukan penarikan dana',
        ],200);
    }

    public function history(Request $request)
    {
        $user = $request->user();
        $history = $user->withdrawal()->orderBy('id','desc')->paginate(20);
        return response([
            'success' => true,
            'data' => $history
        ], 200);
    }
}
