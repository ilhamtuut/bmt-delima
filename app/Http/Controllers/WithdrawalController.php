<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Setting;
use App\Models\Mutation;
use App\Models\Deposito;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
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
        $user = Auth::user();
        if(!$user->bank_name){
            $request->session()->flash('info', 'Mohon isi akun bank terlebih dahulu untuk melakukan penarikan');
            return redirect()->route('profile.bank');
        }
        $history = $user->withdrawal()->orderBy('id','desc')->get();
        return view('pages.withdrawal.index', compact('history'));
    }

    public function send(Request $request)
    {
        $request->merge([
            'nominal' => str_replace('.','',$request->nominal)
        ]);
        $this->validate($request, [
            'nominal' => 'required|numeric'
        ]);
        $min = Setting::where('name','Minimal Withdrawal')->first()->value;
        if($request->nominal < $min){
            $request->session()->flash('failed', 'Minimal penarikan dana Rp'.number_format($min, 0, ',', '.'));
            return redirect()->back();
        }
        $max = Setting::where('name','Maximum Withdrawal')->first()->value;
        if($request->nominal > $max){
            $request->session()->flash('failed', 'Maksimal penarikan dana Rp'.number_format($max, 0, ',', '.'));
            return redirect()->back();
        }
        $user = Auth::user();
        // check balance
        if($user->balance() < $request->nominal){
            $request->session()->flash('failed', 'Saldo Anda tidak cukup atau belum bisa melakukan penarikan');
            return redirect()->back();
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
        $request->session()->flash('success', 'Berhasil melakukan penarikan dana');
        return redirect()->back();
    }

    public function list(Request $request)
    {
        $search = $request->search;
        $status = $request->status;
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

        $whereIn = [0,1,2,3,4];
        if($status == 1){
            $whereIn = [0];
        }elseif($status == 2){
            $whereIn = [1];
        }elseif($status == 3){
            $whereIn = [2];
        }elseif($status == 4){
            $whereIn = [3];
        }elseif($status == 5){
            $whereIn = [4];
        }

        $history = Withdrawal::when($search, function ($query) use ($search){
                $query->whereHas('user', function ($cari) use ($search){
                    $cari->where('users.username', 'LIKE', $search.'%')
                        ->orWhere('users.name', 'LIKE', $search.'%')
                        ->orWhere('users.account_number', 'LIKE', $search.'%');
                });
            })
            ->when($status, function ($query) use ($whereIn){
                $query->whereIn('status',$whereIn);
            })
            ->whereDate('created_at','>=',$from)
            ->whereDate('created_at','<=',$to)
            ->orderBy('id','desc')
            ->paginate(20);

        $total = Withdrawal::when($search, function ($query) use ($search){
                $query->whereHas('user', function ($cari) use ($search){
                    $cari->where('users.username', 'LIKE', $search.'%')
                        ->orWhere('users.name', 'LIKE', $search.'%')
                        ->orWhere('users.account_number', 'LIKE', $search.'%');
                });
            })
            ->when($status, function ($query) use ($whereIn){
                $query->whereIn('status',$whereIn);
            })
            ->whereDate('created_at','>=',$from)
            ->whereDate('created_at','<=',$to)
            ->sum('amount');
        return view('pages.withdrawal.list', compact('history','total'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function action(Request $request,$type,$id)
    {
        $update = ['status' => 1];
        $withdrawal = Withdrawal::find($id);
        $text = 'mengkonfirmasi';
        if($type == 'reject'){
            $text = 'membatalkan';
            $update = ['status' => 2];
            $withdrawal->mutation->delete();
        }
        $withdrawal->update($update);

        $request->session()->flash('success', 'Berhasil '.$text.' penarikan dana.');
        return response()->json(['success' => true ]);
    }
}
