<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurTeacher extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getImageAttribute($value){
        return config('app.url').'/'.$value;
    }
}
