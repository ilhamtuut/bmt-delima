<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    use HasFactory;

    protected $fillable = [
        'trxid',
        'user_id',
        'deposito_id',
        'amount',
        'percent',
        'profit',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            try {
                $model->trxid = self::generateNumber();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }

    public static function generateNumber()
    {
        $code = 'PRFT-'.date('Ymd');
        $format = $code.'000000';
        $latest = self::orderBy('id','desc')->first();
        if($latest){
            $format = $latest->trxid;
        }
        $id = substr($format, -6);
        $newID = intval($id) + 1;
        $newID = str_pad($newID, 6, '0', STR_PAD_LEFT);
        return $code.$newID;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function deposito()
    {
        return $this->belongsTo(Deposito::class, 'deposito_id');
    }

    public function mutation()
    {
        return $this->hasOne(Mutation::class, 'reference_id')
            ->where('reference_type',Profit::class);
    }
}
