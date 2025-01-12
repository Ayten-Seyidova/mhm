<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VideoCourse extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function group()
    {
        return $this->belongsToMany(Group::class, 'groups', 'id', 'id');
    }
    
    public function subjects()
    {
 
        $user = Auth::user();
        $arr = json_decode($user->blocked_subject_ids,1);
        $result = $this->hasMany(Subject::class, 'video_course_id', 'id');

        if($arr){
            $result = $result->whereNotIn('id', $arr);
        }
        
        return $result;
        
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class, 'video_course_id', 'id');
    }
}
