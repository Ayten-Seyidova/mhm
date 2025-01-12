<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function group()
    {
        return $this->belongsToMany(Group::class, 'groups', 'id', 'id');
    }
    
    public function questions()
    {
        return $this->hasMany(Question::class, 'exam_id', 'id')->where("is_deleted",0)->where("status",1);
     }
    
    
}
