<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestExamSubDirection extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function subDirection()
    {
        return $this->hasOne(SubDirection::class, 'id', 'sub_direction_id');
    }
}
