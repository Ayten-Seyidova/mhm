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

    public function groups()
    {
        $groupAdded = @Auth::user()->groupAdded->toArray();
        $result = $this->hasMany(CourseGroup::class,'video_course_id', 'id');
        foreach ($groupAdded as $group) {
            $result = $result->orWhere(function ($query) use ($group) {
                $query->where('group_id', $group['group_id']);

                    if ($group['end_date']) {
                        $query->where('created_at', '<=', $group['end_date']);
                    }
                    if ($group['date']) {
                        $query->where('created_at', '>', $group['date']);
                    }
            });
        }
        return $result;
    }
}
