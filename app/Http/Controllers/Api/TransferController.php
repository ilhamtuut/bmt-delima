<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Setting;
use App\Models\Mutation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransferController extends Controller
{
    

    public function send(Request $request)
    {
        $this->validate($request, [
            'rekening' => 'required|exists:users,account_number',
            'nominal' => 'required|numeric'
        ]);
        $min = Setting::where('name','Minimal Withdrawal')->first()->value;
        if($request->nominal < $min){
            return response()->json([
                'success' => true,
                'message' => 'Minimal transfer Rp'.number_format($min, 0, ',', '.')
            ],422);
        }
        $max = Setting::where('name','Maximum Withdrawal')->first()->value;
        if($request->nominal > $max){
            return response()->json([
                'success' => true,
                'message' => 'Maksimal transfer Rp'.number_format($max, 0, ',', '.')
            ],422);
        }
        $user = $request->user();
        // check balance
        if($user->balance() < $request->nominal){
            return response()->json([
                'success' => true,
                'message' => 'Saldo Anda tidak cukup atau belum bisa melakukan transfer',
            ],422);
        }

        $receiver = User::where('account_number',$request->rekening)->first();
        if(!$receiver){
            return response()->json([
                'success' => true,
                'message' => 'Nomor rekening tidak ditemukan',
            ],422);
        }
        $trxid = 'TRSF-'.date('Ymd').rand(100000,999999);
        Mutation::create([
            'trxid' => $trxid,
            'user_id' => $user->id,
            'note' => 'Transfer ke '.$request->rekening.' - '.strtoupper($receiver->name),
            'amount' => $request->nominal,
            'debit' => 0,
            'kredit' => $request->nominal,
            'reference_id' => $receiver->id,
            'reference_type' => User::class,
            'status' => 1
        ]);
        Mutation::create([
            'trxid' => $trxid,
            'user_id' => $receiver->id,
            'note' => 'Transfer Rp'.number_format($request->nominal, 0, ',', '.').' dari '.strtoupper($user->name),
            'amount' => $request->nominal,
            'debit' => $request->nominal,
            'kredit' => 0,
            'reference_id' => $user->id,
            'reference_type' => User::class,
            'status' => 1
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil melakukan transfer dana',
        ],200);
    }

    public function valid_account(Request $request)
    {
        $this->validate($request, [
            'rekening' => 'required|string'
        ]);

        $account = User::select('account_number','name')->where('account_number',$request->rekening)->first();
        if(!$account){
            return response()->json([
                'success' => false,
                'message' => 'Nomor rekening tidak ditemukan'
            ],422);
        }
        return response()->json([
            'success' => true,
            'message' => 'Nomor rekening sesuai',
            'data' => $account
        ],200);
    }
}
