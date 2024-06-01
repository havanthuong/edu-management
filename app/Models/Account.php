<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Account extends Authenticatable implements JWTSubject
{

    use Notifiable;

    protected $table = 'Account';

    protected $fillable = [
        'userName', 'password', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isAdmin()
    {
        return $this->role === 3;
    }

    public function isTeacher()
    {
        return $this->role === 2;
    }

    public function isStudent()
    {
        return $this->role === 1;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($account) {
            $account->password = Hash::make($account->password);
        });

        static::updating(function ($account) {
            if ($account->isDirty('password')) {
                $account->password = Hash::make($account->password);
            }
        });
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
