<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'User';

    protected $fillable = [
        'name',
        'email',
        'gender',
        'address',
        'accountId',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'accountId');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'userId');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'userId');
    }
}
