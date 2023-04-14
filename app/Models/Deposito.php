<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposito extends Model
{
    use HasFactory;
    protected $fillable = [
        'trxid',
        'user_id',
        'deposito_type_id',
        'amount',
        'profit',
        'code',
        'status',
        'expired_at'
    ];

    protected $appends = ['status_text','label'];

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
        $code = 'DPST-'.date('Ymd');
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

    public function type()
    {
        return $this->belongsTo(DepositoType::class, 'deposito_type_id');
    }

    public function mutation()
    {
        return $this->hasOne(Mutation::class, 'reference_id')
            ->where('reference_type',Deposito::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'deposito_id');
    }

    public function profit()
    {
        return $this->hasMany(Profit::class, 'deposito_id');
    }

    public function getStatusTextAttribute()
    {
        $status = 'Menunggu Pembayaran';
        if($this->attributes['status'] == 1){
            $status = 'Aktif';
        }else if($this->attributes['status'] == 2){
            $status = 'Menunggu Konfirmasi';
        }else if($this->attributes['status'] == 3){
            $status = 'Batal';
        }else if($this->attributes['status'] == 4){
            $status = 'Selesai';
        }
        return $status;
    }

    public function getLabelAttribute()
    {
        $status = 'warning';
        if($this->attributes['status'] == 1){
            $status = 'info';
        }else if($this->attributes['status'] == 2){
            $status = 'danger';
        }else if($this->attributes['status'] == 3){
            $status = 'primary';
        }else if($this->attributes['status'] == 4){
            $status = 'success';
        }
        return $status;
    }
}
