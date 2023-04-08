<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'trxid',
        'user_id',
        'note',
        'amount',
        'debit',
        'kredit',
        'reference_id',
        'reference_type',
        'status'
    ];
    protected $appends = ['type'];

    public function getTypeAttribute()
    {
        $type = 'Debit';
        if($this->attributes['kredit'] > 0){
            $type = 'Kredit';
        }
        return $type;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reference()
    {
        return $this->hasOne($this->attributes['journal_type'], 'reference_id','id');
    }
}
