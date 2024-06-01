<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'Attendance';

    protected $fillable = [
        'studentId',
        'sessionId',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'studentId');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'sessionId');
    }
}
