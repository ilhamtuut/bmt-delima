<?php

namespace App\Http\Controllers\Api;

use App\Models\Bank;
use App\Models\BankDeposit;
use App\Models\DepositoType;
use App\Models\Deposito;
use App\Models\Payment;
use App\Models\Profit;
use App\Rules\Base64Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepositoController extends Controller
{
    public function send(Request $request)
    {
        $this->validate($request, [
            'deposito_type' => 'required|exists:deposito_types,id',
            'payment_option' => 'required|exists:bank_deposits,id',
            'nominal' => 'required|numeric'
        ]);
        $user = $request->user();
        $paket = DepositoType::find($request->deposito_type);
        if($request->nominal < $paket->minimal){
            return response([
                'success' => false,
                'message' => 'Nominal deposito kurang dari Rp'.number_format($paket->minimal,0,',','.')
            ], 422);
        }
        $code = rand(110,999);
        $deposito = Deposito::create([
            'user_id' => $user->id,
            'deposito_type_id' => $request->deposito_type,
            'amount' => $request->nominal,
            'profit' => $request->nominal * $paket->percent,
            'code' => $code,
            'status' => 0
        ]);

        $payment = BankDeposit::find($request->payment_option);
        Payment::create([
            'deposito_id' => $deposito->id,
            'bank_code' => $payment->bank_code,
            'bank_name' => $payment->bank_name,
            'bank_account_name' => $payment->bank_account_name,
            'bank_account_number' => $payment->bank_account_number,
        ]);
        $data = Deposito::with('payment:id,deposito_id,bank_code,bank_name,bank_account_name,bank_account_number')->find($deposito->id);
        return response([
            'success' => true,
            'message' => 'Berhasil mengajukan deposito baru, mohon segera melakukan pembayaran',
            'data' => $data
        ], 200);
    }

    public function confirm_payment(Request $request,$id)
    {   
        $this->validate($request, [
            'nominal' => 'required|numeric',
            'file' => ['required', new Base64Rule()],
        ]);

        $user = $request->user();
        $deposito = Deposito::where(['id'=>$id, 'user_id' => $user->id, 'status' => 0])->first();
        if(!$deposito){
            return response([
                'success' => true,
                'message' => 'Deposito tidak ditemukan.',
            ], 422);
        }
        $deposito->update([
            'status' => 2
        ]);

        $image = $request->file;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = uniqid().'.'.'png';
        \File::put('file/payment/' . $imageName, base64_decode($image));

        Payment::where('deposito_id',$id)->update([
            'amount' => $request->nominal,
            'proof_of_payment' => $imageName,
            'status' => 1
        ]);

        return response([
            'success' => true,
            'message' => 'Berhasil mengkonfirmasi pembayaran, mohon menunggu konfirmasi dari kami.',
        ], 200);
    }

    public function history(Request $request)
    {
        $user = $request->user();
        $history = $user->deposito()->with('payment')->orderBy('id','desc')->paginate(20);
        return response([
            'success' => true,
            'data' => $history
        ], 200);
    }

    public function my_profit(Request $request, $deposito_id)
    {
        $from_date = str_replace('/', '-', $request->from_date);
        $to_date = str_replace('/', '-', $request->to_date);
        if($from_date && $to_date){
            $from = date('Y-m-d',strtotime($from_date));
            $to = date('Y-m-d',strtotime($to_date));
        }else{
            $from = date('Y-m-d',strtotime('01/01/2018'));
            $to = date('Y-m-d');
            $from_date = '01/01/2018';
            $to_date = date('d/m/Y');
        }

        $user = $request->user();
        $history = Profit::where('user_id',$user->id)
            ->where('deposito_id',$deposito_id)
            ->whereDate('created_at','>=',$from)
            ->whereDate('created_at','<=',$to)
            ->paginate(30);

        $total = Profit::where('user_id',$user->id)
            ->where('deposito_id',$deposito_id)
            ->whereDate('created_at','>=',$from)
            ->whereDate('created_at','<=',$to)
            ->sum('amount');
        return response([
            'success' => true,
            'total' => $total,
            'data' => $history
        ], 200);
    }
}
