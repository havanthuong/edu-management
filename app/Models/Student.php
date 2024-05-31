<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'Student';

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

    public function classRegistrations()
    {
        return $this->hasMany(ClassRegistration::class, 'studentId');
    }

    public function classStudents()
    {
        return $this->hasMany(ClassStudent::class, 'studentId');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'studentId');
    }
}
