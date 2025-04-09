<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestQuestion extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function exam()
    {
        return $this->hasOne(GuestExam::class, 'id', 'guest_exam_id');
    }
}
