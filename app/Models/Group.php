<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function teacher()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    
    public function getImageAttribute($value){

        return config('app.url').'/'.$value;

    }
}
