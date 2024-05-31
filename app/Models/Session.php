<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'Session';

    protected $fillable = [
        'classId',
        'sesionDate',
        'sesionLocation',
        'studentId',
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'classId');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'sessionId');
    }
}
