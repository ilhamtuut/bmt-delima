<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Profit;
use App\Models\Payment;
use App\Models\Mutation;
use App\Models\Deposito;
use App\Models\Affiliate;
use App\Models\BankDeposit;
use App\Models\DepositoType;
use Illuminate\Http\Request;

class DepositoController extends Controller
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
        $payment = BankDeposit::get();
        $deposito = DepositoType::get();
        $history = $user->deposito()->orderBy('id','desc')->get();
        return view('pages.deposito.index', compact('deposito','payment','history'));
    }

    public function save(Request $request)
    {
        $request->merge([
            'nominal' => str_replace('.','',$request->nominal)
        ]);
        $this->validate($request, [
            'deposito' => 'required|exists:deposito_types,id',
            'nominal' => 'required|numeric',
            'payment' => 'required|exists:bank_deposits,id'
        ]);
        $user = Auth::user();
        $paket = DepositoType::find($request->deposito);
        if($request->nominal < $paket->minimal){
            $request->session()->flash('failed', 'Nominal deposito kurang dari Rp'.number_format($paket->minimal,0,',','.'));
            return redirect()->back();
        }
        $code = rand(110,999);
        $deposito = Deposito::create([
            'user_id' => $user->id,
            'deposito_type_id' => $request->deposito,
            'amount' => $request->nominal,
            'profit' => $request->nominal * $paket->percent,
            'code' => $code,
            'status' => 0
        ]);

        $payment = BankDeposit::find($request->payment);
        Payment::create([
            'deposito_id' => $deposito->id,
            'bank_code' => $payment->bank_code,
            'bank_name' => $payment->bank_name,
            'bank_account_name' => $payment->bank_account_name,
            'bank_account_number' => $payment->bank_account_number,
        ]);
        $request->session()->flash('success', 'Berhasil mengajukan deposito baru, mohon segera melakukan pembayaran');
        return redirect()->to('deposito/payment/'.$deposito->id);
    }

    public function payment(Request $request, $id)
    {
        $deposito = Deposito::where(['id' => $id,'user_id'=>Auth::id()])->first();
        if(!$deposito){
            return redirect()->to('deposito');
        }
        return view('pages.deposito.payment', compact('deposito'));
    }

    public function confirm_payment(Request $request,$id)
    {
        $request->merge([
            'nominal' => str_replace('.','',$request->nominal)
        ]);

        $this->validate($request, [
            'nominal' => 'required|numeric',
            'file' => ['required', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);
        $user = Auth::user();
        $deposito = Deposito::where(['id'=>$id, 'user_id' => $user->id, 'status' => 0])->first();
        if(!$deposito){
            $request->session()->flash('success', 'Deposito tidak ditemukan.');
            return redirect()->back();
        }
        $deposito->update([
            'status' => 2
        ]);

        $file = $request->file('file');
        $filename = uniqid().'.'.$file->getClientOriginalExtension();
        $file->move('file/payment/',$filename);

        Payment::where('deposito_id',$id)->update([
            'amount' => $request->nominal,
            'proof_of_payment' => $filename,
            'status' => 1
        ]);

        $request->session()->flash('success', 'Berhasil mengkonfirmasi pembayaran, mohon menunggu konfirmasi dari kami.');
        return redirect()->back();
    }

    public function action_deposito(Request $request,$type,$id)
    {
        $status_payment = 3;
        $text = 'membatalkan';
        $update = [
            'status' => 3
        ];
        $deposito = Deposito::find($id);
        if($type == 'accept'){
            $status_payment = 2;
            $text = 'mengkonfirmasi';
            $expired_at = date('Y-m-d', strtotime(date("Y-m-d"). ' + '.$deposito->type->contract.' month'));
            $update = [
                'expired_at' => $expired_at,
                'status' => 1
            ];
        }
        $deposito->update($update);

        Payment::where('deposito_id',$id)->update([
            'status' => $status_payment
        ]);

        if($type == 'accept'){
            Mutation::create([
                'trxid' => $deposito->trxid,
                'user_id' => $deposito->user_id,
                'note' => $deposito->type->name,
                'amount' => $deposito->amount,
                'debit' => $deposito->amount,
                'kredit' => 0,
                'reference_id' => $deposito->id,
                'reference_type' => Deposito::class,
                'status' => 1
            ]);
            $this->affiliasi($deposito->id,$deposito->user_id,$deposito->amount);
        }

        $request->session()->flash('success', 'Berhasil '.$text.' pembayaran deposito.');
        return response()->json(['success' => true ]);
    }

    public function add(Request $request)
    {
        $deposito = DepositoType::get();
        $users = User::select('id','account_number','name')
            ->where('status',1)
            ->whereNotNull('email_verified_at')
            ->whereHas('roles', function ($query) {
                $query->where('roles.name', 'member');
            })->get();
        return view('pages.deposito.add', compact('deposito','users'));
    }

    public function create(Request $request)
    {
        $request->merge([
            'nominal' => str_replace('.','',$request->nominal)
        ]);

        $this->validate($request, [
            'account_number' => 'required|exists:users,id',
            'deposito' => 'required|exists:deposito_types,id',
            'nominal' => 'required|numeric',
        ]);
        $paket = DepositoType::find($request->deposito);
        if($request->nominal < $paket->minimal){
            $request->session()->flash('failed', 'Nominal deposito kurang dari Rp'.number_format($paket->minimal,0,',','.'));
            return redirect()->back();
        }
        $expired_at = date('Y-m-d', strtotime(date("Y-m-d"). ' + '.$paket->contract.' month'));
        $deposito = Deposito::create([
            'user_id' => $request->account_number,
            'deposito_type_id' => $request->deposito,
            'amount' => $request->nominal,
            'profit' => $request->nominal * $paket->percent,
            'code' => 0,
            'status' => 1,
            'expired_at' => $expired_at
        ]);

        Mutation::create([
            'trxid' => $deposito->trxid,
            'user_id' => $request->account_number,
            'note' => $paket->name,
            'amount' => $request->nominal,
            'debit' => $request->nominal,
            'kredit' => 0,
            'reference_id' => $deposito->id,
            'reference_type' => Deposito::class,
            'status' => 1
        ]);
        $request->session()->flash('success', 'Berhasil membuat deposito baru');
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

        $history = Deposito::when($search, function ($query) use ($search){
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

        $total = Deposito::when($search, function ($query) use ($search){
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
        $profit = Deposito::when($search, function ($query) use ($search){
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
            ->sum('profit');
        return view('pages.deposito.list', compact('history','total','profit'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function affiliasi($deposito_id,$user_id,$amount)
    {
        $bonus = $amount * 0.01;
        $user = User::where('id',$user_id)->whereNotNull('parent_id')->first();
        if($user){
            $parent_id = $user->parent_id;
            $affiliate = Affiliate::create([
                'deposito_id' => $deposito_id,
                'user_id' => $parent_id,
                'from_id' => $user_id,
                'amount' => $amount,
                'percent' => 0.01,
                'bonus' => $bonus,
            ]);

            Mutation::create([
                'trxid' => $affiliate->trxid,
                'user_id' => $affiliate->user_id,
                'note' => 'Komisi Affiliasi dari '.strtoupper($user->name),
                'amount' => $affiliate->bonus,
                'debit' => $affiliate->bonus,
                'kredit' => 0,
                'reference_id' => $affiliate->id,
                'reference_type' => Affiliate::class,
                'status' => 1
            ]);
        }
        return true;
    }

    public function list_affilate(Request $request)
    {
        $search = $request->search;
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

        $history = Affiliate::when($search, function ($query) use ($search){
                $query->whereHas('user', function ($cari) use ($search){
                    $cari->where('users.username', 'LIKE', $search.'%')
                        ->orWhere('users.name', 'LIKE', $search.'%')
                        ->orWhere('users.account_number', 'LIKE', $search.'%');
                });
            })
            ->whereDate('created_at','>=',$from)
            ->whereDate('created_at','<=',$to)
            ->orderBy('id','desc')
            ->paginate(20);

        $total = Affiliate::when($search, function ($query) use ($search){
                $query->whereHas('user', function ($cari) use ($search){
                    $cari->where('users.username', 'LIKE', $search.'%')
                        ->orWhere('users.name', 'LIKE', $search.'%')
                        ->orWhere('users.account_number', 'LIKE', $search.'%');
                });
            })
            ->whereDate('created_at','>=',$from)
            ->whereDate('created_at','<=',$to)
            ->sum('bonus');
        return view('pages.deposito.affilate', compact('history','total'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function list_profit(Request $request)
    {
        $search = $request->search;
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

        $history = Profit::when($search, function ($query) use ($search){
                $query->whereHas('user', function ($cari) use ($search){
                    $cari->where('users.username', 'LIKE', $search.'%')
                        ->orWhere('users.name', 'LIKE', $search.'%')
                        ->orWhere('users.account_number', 'LIKE', $search.'%');
                });
            })
            ->whereDate('created_at','>=',$from)
            ->whereDate('created_at','<=',$to)
            ->orderBy('id','desc')
            ->paginate(20);

        $total = Profit::when($search, function ($query) use ($search){
                $query->whereHas('user', function ($cari) use ($search){
                    $cari->where('users.username', 'LIKE', $search.'%')
                        ->orWhere('users.name', 'LIKE', $search.'%')
                        ->orWhere('users.account_number', 'LIKE', $search.'%');
                });
            })
            ->whereDate('created_at','>=',$from)
            ->whereDate('created_at','<=',$to)
            ->sum('amount');
        return view('pages.deposito.profit', compact('history','total'))->with('i', (request()->input('page', 1) - 1) * 20);
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

        $history = Profit::where('user_id',Auth::id())
            ->where('deposito_id',$deposito_id)
            ->whereDate('created_at','>=',$from)
            ->whereDate('created_at','<=',$to)
            ->paginate(24);

        $total = Profit::where('user_id',Auth::id())
            ->where('deposito_id',$deposito_id)
            ->whereDate('created_at','>=',$from)
            ->whereDate('created_at','<=',$to)
            ->sum('amount');
        return view('pages.deposito.my_profit', compact('history','total', 'deposito_id'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function custom(Request $request)
    {
        $users = User::select('id','account_number','name')
            ->where('status',1)
            ->whereNotNull('email_verified_at')
            ->whereHas('roles', function ($query) {
                $query->where('roles.name', 'member');
            })->get();
        return view('pages.deposito.custom', compact('users'));
    }

    public function createCustom(Request $request)
    {
        $request->merge([
            'nominal' => str_replace('.','',$request->nominal)
        ]);
        $this->validate($request, [
            'account_number' => 'required|exists:users,id',
            'contract' => 'required|numeric',
            'percent' => 'required|numeric',
            'nominal' => 'required|numeric',
        ]);
        $paket = DepositoType::where('name','Custom Deposito')->first();
        $expired_at = date('Y-m-d', strtotime(date("Y-m-d"). ' + '.$request->contract.' month'));
        $deposito = Deposito::create([
            'user_id' => $request->account_number,
            'deposito_type_id' => $paket->id,
            'amount' => $request->nominal,
            'profit' => $request->nominal * ($request->percent/100),
            'code' => 0,
            'status' => 1,
            'type_deposito' => 'custom',
            'contract' => $request->contract,
            'percent' => $request->percent/100,
            'expired_at' => $expired_at
        ]);

        Mutation::create([
            'trxid' => $deposito->trxid,
            'user_id' => $request->account_number,
            'note' => $paket->name.' '.$request->contract.' Bulan '.$request->percent.'%',
            'amount' => $request->nominal,
            'debit' => $request->nominal,
            'kredit' => 0,
            'reference_id' => $deposito->id,
            'reference_type' => Deposito::class,
            'status' => 1
        ]);
        $request->session()->flash('success', 'Berhasil membuat deposito baru');
        return redirect()->back();
    }
}
