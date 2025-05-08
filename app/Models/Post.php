<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['liked'];

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
        return $this->hasMany(Variant::class, 'post_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(GuestComment::class, 'post_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class, 'post_id', 'id');
    }

    public function getLikedAttribute()
    {
        if (!Auth::guard("apiGuest")->check()) return false;

        return $this->likes->contains('guest_id', Auth::user()->id);
    }
}
