<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Guest extends Model
{
    use HasFactory, HasApiTokens;

    protected $guarded = [];
    protected $hidden = ['password'];

    public function subDirection()
    {
        return $this->hasOne(SubDirection::class, 'id', 'sub_direction_id');
    }

    public function getImageAttribute($value)
    {
        return config('app.url') . '/' . $value;
    }

    public function getIsStudentAttribute($value)
    {
        return  (int)$value;
    }
}
