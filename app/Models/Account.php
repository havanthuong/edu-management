<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'Account';

    protected $fillable = [
        'userName',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    public function sessions()
    {
        return $this->hasMany(AccountSession::class, 'accountId');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'accountId');
    }
}
