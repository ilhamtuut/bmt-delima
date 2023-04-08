<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Setting;
use App\Models\BankDeposit;
use App\Models\DepositoType;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $data = Setting::get();
        return view('pages.settings.index', compact('data'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:settings,id',
            'nominal'=>'required|numeric'
        ]);

        $data = Setting::find($request->id);
        if($data->type == "%"){
            $amount = $request->nominal/100;
        }else{
            $amount = $request->nominal;
        }
        $data->value = $amount;
        $data->save();
        $request->session()->flash('success', 'Successfully updated data');
        return redirect()->back();
    }
    
    public function bank(Request $request)
    {
        $data = Bank::get();
        return view('pages.settings.bank', compact('data'));
    }

    public function store_bank(Request $request)
    {
        $this->validate($request, [
            'id' => 'nullable|exists:banks,id',
            'code'=>'required',
            'name'=>'required'
        ]);
        if($request->id){
            Bank::find($request->id)->update($request->all());
        }else{
            Bank::create($request->all());
        }
        $request->session()->flash('success', 'Successfully save changes data');
        return redirect()->back();
    }
    
    public function bank_account(Request $request)
    {
        $banks = Bank::get();
        $data = BankDeposit::get();
        return view('pages.settings.bank_account', compact('data','banks'));
    }

    public function store_bank_account(Request $request)
    {
        $this->validate($request, [
            'id' => 'nullable|exists:bank_deposits,id',
            'bank_code' => 'required|string',
            'bank_name' => 'required|string',
            'bank_account_name' => 'required|string',
            'bank_account_number' => 'required|string',
        ]);
        if($request->id){
            BankDeposit::find($request->id)->update($request->all());
        }else{
            BankDeposit::create($request->all());
        }
        $request->session()->flash('success', 'Successfully save changes data');
        return redirect()->back();
    }

    public function deposito(Request $request)
    {
        $data = DepositoType::get();
        return view('pages.settings.deposito', compact('data'));
    }

    public function store_deposito(Request $request)
    {
        $request->merge([
            'percent' => $request->percent/100
        ]);
        $this->validate($request, [
            'id' => 'nullable|exists:deposito_types,id',
            'name' => 'required|string',
            'contract' => 'required|integer',
            'percent' => 'required|numeric',
            'minimal' => 'required|numeric',
        ]);
        if($request->id){
            DepositoType::find($request->id)->update($request->all());
        }else{
            DepositoType::create($request->all());
        }
        $request->session()->flash('success', 'Successfully save changes data');
        return redirect()->back();
    }
}
