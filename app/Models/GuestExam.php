<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestExam extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function teacher()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function guestExamSubDirections()
    {
        return $this->hasMany(GuestExamSubDirection::class, 'guest_exam_id');
    }

    public function questions()
    {
        return $this->hasMany(GuestQuestion::class, 'guest_exam_id')->where('is_deleted',0);
    }
}
