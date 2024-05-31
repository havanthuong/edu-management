<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'Department';

    protected $fillable = [
        'name',
    ];

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'departmentId');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'departmentId');
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'departmentId');
    }
}
