<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestResult extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function guest()
    {
        return $this->hasOne(Guest::class, 'id', 'guest_id');
    }

    public function guestExam()
    {
        return $this->hasOne(GuestExam::class, 'id', 'guest_exam_id');
    }
}
