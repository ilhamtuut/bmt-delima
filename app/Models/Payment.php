<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'deposito_id',
        'amount',
        'bank_code',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'proof_of_payment',
        'status',
    ];
    protected $appends = ['link'];

    public function getLinkAttribute()
    {
        if(@$this->attributes['proof_of_payment']){
            return asset('file/payment/'.$this->attributes['proof_of_payment']);
        }
    }

    public function deposito()
    {
        return $this->belongsTo(Deposito::class, 'deposito_id');
    }
}
