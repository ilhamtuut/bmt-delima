<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MutationController extends Controller
{
    public function list(Request $request)
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
        $data = $user->mutation()
            // ->with('reference')
            ->whereDate('created_at','>=',$from)
            ->whereDate('created_at','<=',$to)
            ->orderBy('id','desc')
            ->paginate(20)->withQueryString();
        $saldo = $user->saldo();
        return response([
            'success' => true,
            'balance' => $saldo,
            'data' => $data
        ], 200);
    }
}
