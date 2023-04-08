<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Profit;
use App\Models\Deposito;
use App\Models\Withdrawal;
use App\Models\DepositoType;
use Illuminate\Http\Request;

class HomeController extends Controller
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
    public function index()
    {
        $user = Auth::user();
        if($user->hasRole('member')){
            $types = DepositoType::get();
            $deposito = $user->deposito()->whereIn('status',[1,4])->sum('amount');
            $profit = $user->deposito()->whereIn('status',[1,4])->sum('profit');
            // $profit = $user->profit()->where('status',1)->sum('profit');
            $withdraw = $user->withdrawal()->where('status',1)->sum('amount');
            return view('home', compact('types','deposito','profit','withdraw'));
        }else{
            $users = User::whereHas('roles', function ($query) {
                $query->where('roles.name', 'member');
            })->count();
            $deposito = Deposito::where('status',1)->sum('amount');
            $profit = Deposito::where('status',1)->sum('profit');
            // $profit = Profit::where('status',1)->sum('profit');
            $withdraw = Withdrawal::where('status',1)->sum('amount');
            return view('home', compact('users','deposito','profit','withdraw'));
        }
    }
}
