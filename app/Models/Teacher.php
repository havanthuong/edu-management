<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'Teacher';

    protected $fillable = [
        'userId',
        'departmentId',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'departmentId');
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'teacherId');
    }

    public function getUsernameAttribute()
    {
        return $this->user->account->userName ?? null;
    }
}
