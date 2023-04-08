<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDeposit extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'bank_code',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'status'
    ];
}
