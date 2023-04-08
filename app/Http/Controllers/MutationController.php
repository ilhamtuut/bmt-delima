<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Mutation;
use Illuminate\Http\Request;

class MutationController extends Controller
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
        $user = Auth::user();
        $data = $user->mutation()
            ->whereDate('created_at','>=',$from)
            ->whereDate('created_at','<=',$to)
            ->orderBy('id','desc')
            ->paginate(20);
        $saldo = $user->saldo();
        return view('pages.mutation.index', compact('data','saldo'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function balance(Request $request)
    {
        $search = $request->search;
        $data = User::whereHas('roles', function ($query) {
                    $query->where('roles.name', 'member');
                })
                ->whereNotNull('email_verified_at')
                ->when($search, function ($cari) use ($search) {
                    return $cari->where('username', 'LIKE' ,$search.'%')
                    ->orWhere('account_number', 'LIKE', $search.'%')
                    ->orWhere('name', 'LIKE', $search.'%');
                })->paginate(20);
        return view('pages.mutation.balance', compact('data'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function history(Request $request,$id)
    {
        $user = User::find($id);
        $data = Mutation::where('user_id',$id)->orderBy('id','asc')->get();
        return view('pages.mutation.history', compact('user','data'));
    }
}
