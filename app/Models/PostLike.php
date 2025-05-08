<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'post_like';

    public function post()
    {
        return $this->hasOne(Post::class, 'id', 'post_id');
    }

    public function guest()
    {
        return $this->hasOne(Guest::class, 'id', 'guest_id');
    }
}
