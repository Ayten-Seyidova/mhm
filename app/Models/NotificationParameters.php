<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationParameters extends Model
{
    use HasFactory;
    protected $guarded = [];

//    protected $table = 'notification_parameters';


    public function user()
    {
        return $this->hasMany(Customer::class, 'id', 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id', 'id');
    }
}
