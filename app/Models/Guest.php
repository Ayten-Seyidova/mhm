<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function subDirection()
    {
        return $this->hasOne(SubDirection::class, 'id', 'sub_direction_id');
    }

    public function getImageAttribute($value)
    {
        return config('app.url') . '/' . $value;
    }
}
