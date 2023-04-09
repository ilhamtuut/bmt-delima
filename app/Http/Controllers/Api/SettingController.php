<?php

namespace App\Http\Controllers\Api;

use App\Models\Bank;
use App\Models\BankDeposit;
use App\Models\DepositoType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function bank(Request $request)
    {
        $data = Bank::select('code','name')->get();
        return response([
            'success' => true,
            'data' => $data
        ], 200);
    }

    public function bank_account(Request $request)
    {
        $data = BankDeposit::get();
        return response([
            'success' => true,
            'data' => $data
        ], 200);
    }

    public function deposito(Request $request)
    {
        $data = DepositoType::get();
        return response([
            'success' => true,
            'data' => $data
        ], 200);
    }
}
