<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpPhones extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'otp_phones_list';


}
