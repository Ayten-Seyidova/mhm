<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function teacher()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function subDirection()
    {
        return $this->hasOne(SubDirection::class, 'id', 'sub_direction_id');
    }

    public function getImageAttribute($value){
        return config('app.url').'/'.$value;
    }

    public function variants()
    {
        return $this->hasMany(Variant::class, 'id', 'post_id');
    }
}
