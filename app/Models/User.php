<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_number',
        'name',
        'last_name',
        'username',
        'email',
        'phone_number',
        'address',
        'ktp',
        'file_ktp',
        'bank_code',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'foto_profile',
        'parent_id',
        'is_referral',
        'referral_code',
        'status',
        'session_id',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'session_id',
        'remember_token',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            try {
                $model->account_number = self::generateNumber();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }

    public static function generateNumber()
    {
        $code = '690-1';
        $format = $code.'000000';
        $latest = self::orderBy('id','desc')->first();
        if($latest){
            $format = $latest->account_number;
        }
        $id = substr($format, -6);
        $newID = intval($id) + 1;
        $newID = str_pad($newID, 6, '0', STR_PAD_LEFT);
        return $code.$newID;
    }

    protected $appends = ['link_ktp','foto_profile_link'];

    public function getLinkKtpAttribute()
    {
        if(@$this->attributes['file_ktp']){
            return asset('file/ktp/'.$this->attributes['file_ktp']);
        }
    }

    public function getFotoProfileLinkAttribute()
    {
        if(@$this->attributes['foto_profile']){
            return asset('file/profile/'.$this->attributes['foto_profile']);
        }
        return asset('assets/img/avatars/1.png');
    }

    public function getPhoneNumberAttribute()
    {
        return '+62'.$this->attributes['phone_number'];
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function childs()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(User::class, 'parent_id')->with('children');
    }

    public function saldo()
    {
        return $this->hasMany(Mutation::class, 'user_id')->sum('debit') - $this->hasMany(Mutation::class, 'user_id')->sum('kredit');
    }

    public function balance()
    {
        return $this->hasMany(Mutation::class, 'user_id')->sum('debit') - $this->hasMany(Mutation::class, 'user_id')->sum('kredit') - $this->hasMany(Deposito::class, 'user_id')->where('status',1)->sum('amount');
    }

    public function debit()
    {
        return $this->hasMany(Mutation::class, 'user_id')->sum('debit');
    }

    public function kredit()
    {
        return $this->hasMany(Mutation::class, 'user_id')->sum('kredit');
    }

    public function mutation()
    {
        return $this->hasMany(Mutation::class, 'user_id');
    }

    public function deposito()
    {
        return $this->hasMany(Deposito::class, 'user_id');
    }

    public function profit()
    {
        return $this->hasMany(Profit::class, 'user_id');
    }

    public function withdrawal()
    {
        return $this->hasMany(Withdrawal::class, 'user_id');
    }

    public function withdraw()
    {
        return $this->hasMany(Withdrawal::class, 'user_id')->where('status',1)->sum('amount');
    }

}
