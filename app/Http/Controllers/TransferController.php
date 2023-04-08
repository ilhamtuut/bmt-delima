<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Setting;
use App\Models\Mutation;
use Illuminate\Http\Request;

class TransferController extends Controller
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
        return view('pages.transfer.index');
    }

    public function send(Request $request)
    {
        $request->merge([
            'nominal' => str_replace('.','',$request->nominal)
        ]);
        $this->validate($request, [
            'rekening' => 'required|exists:users,account_number',
            'nominal' => 'required|numeric'
        ]);
        $min = Setting::where('name','Minimal Withdrawal')->first()->value;
        if($request->nominal < $min){
            $request->session()->flash('failed', 'Minimal transfer Rp'.number_format($min, 0, ',', '.'));
            return redirect()->back();
        }
        $max = Setting::where('name','Maximum Withdrawal')->first()->value;
        if($request->nominal > $max){
            $request->session()->flash('failed', 'Maksimal transfer Rp'.number_format($max, 0, ',', '.'));
            return redirect()->back();
        }
        $user = Auth::user();
        // check balance
        if($user->balance() < $request->nominal){
            $request->session()->flash('failed', 'Saldo Anda tidak cukup atau belum bisa melakukan transfer');
            return redirect()->back();
        }

        $receiver = User::where('account_number',$request->rekening)->first();
        if(!$receiver){
            $request->session()->flash('failed', 'Nomor rekening tidak ditemukan');
            return redirect()->back();
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
        $request->session()->flash('success', 'Berhasil melakukan transfer dana');
        return redirect()->back();
    }

    public function valid_account(Request $request)
    {
        $account = User::select('account_number','name')->where('account_number',$request->account)->first();
        if(!$account){
            return response()->json([
                'success' => false,
                'message' => 'Nomor rekening tidak ditemukan'
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Nomor rekening sesuai',
            'data' => $account
        ]);
    }
}
