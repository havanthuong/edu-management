<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'Class';

    protected $fillable = [
        'name',
        'teacherId',
        'courseName',
        'startDate',
        'endDate',
        'status',
        'numberOfSession',
        'departmentId',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacherId');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'departmentId');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class, 'classId');
    }

    public function registrations()
    {
        return $this->hasMany(ClassRegistration::class, 'classId');
    }

    public function students()
    {
        return $this->hasMany(ClassStudent::class, 'classId');
    }
}
