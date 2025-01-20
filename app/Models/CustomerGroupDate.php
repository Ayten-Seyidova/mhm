<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerGroupDate extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function group()
    {
        return $this->hasOne(Group::class, 'id', 'group_id');
    }
}
