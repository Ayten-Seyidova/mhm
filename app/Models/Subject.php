<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function course()
    {
        return $this->hasOne(VideoCourse::class, 'id', 'video_course_id');
    }
    
    public function videos()
    {
        return $this->hasMany(Video::class, 'subject_id', 'id')->where("is_deleted",0);
    }
}
