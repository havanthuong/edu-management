<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassStudent extends Model
{
    use HasFactory;

    protected $table = 'ClassStudent';

    protected $fillable = [
        'studentId',
        'classId',
        'score',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'studentId');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'classId');
    }
}
