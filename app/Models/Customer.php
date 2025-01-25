<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Customer extends Model
{
    use HasFactory,HasApiTokens;

    protected $fillable = [
        'image',
        'email',
        'password',
        'password_text',
        'username',
        'class',
        'device_id',
        'blocked_subject_ids',
        'group_ids',
        'date',
        'is_deleted',
        'status',
    ];

    protected $hidden = ["password"];


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    // public function group()
    // {
    //     return $this->belongsToMany(Group::class, 'groups', 'id', 'id');
    // }

    // public function subject()
    // {
    //     return $this->belongsToMany(Subject::class, 'subjects', 'id', 'id');
    // }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            $lastOrder = Customer::orderBy('id', 'desc')->first();

            if ($lastOrder && preg_match('/user(\d+)/', $lastOrder->username, $matches)) {
                $lastNumber = (int)$matches[1];
            } else {
                $lastNumber = 0;
            }

            $newNumber = $lastNumber + 1;

            $prefix = 'user';
            $number = (string)$newNumber;

            $customerNumber = $prefix . $number;

            $customer->username = $customerNumber;
            $password = $customerNumber . rand(10000, 99999);
            $customer->password = bcrypt($password);
            $customer->password_text = $password;
        });
    }

      public function getImageAttribute($value){

        return config('app.url').'/'.$value;

    }

    public function parameters()
    {
        return $this->hasOne(NotificationParameters::class, 'user_id', 'id');
    }


    public function groupAdded()
    {
        return $this->hasMany(CustomerGroupDate::class, 'customer_id', 'id');
    }

}
