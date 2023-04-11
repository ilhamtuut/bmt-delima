<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use HasFactory;

    protected $fillable = [
        'trxid',
        'deposito_id',
        'user_id',
        'from_id',
        'amount',
        'percent',
        'bonus',
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
        $code = 'AFLT-'.date('Ymd');
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

    public function from()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    public function deposito()
    {
        return $this->belongsTo(Deposito::class, 'deposito_id');
    }
}
