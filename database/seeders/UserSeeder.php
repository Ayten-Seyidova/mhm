<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Guest;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "name" => "admin",
            "email" => "info@mhm.az",
            "password" => bcrypt("12345678")
        ]);
//        Guest::create([
//            "name" => "admin",
//            "phone"=>"994709990569",
//            "password" => bcrypt("12345678")
//        ]);
//        Customer::create([
//            "username" => "albert",
//            "name" => "albert",
//            "password" => bcrypt("12345678"),
//            "password_text" => "12345678",
//            "email"=>"alberthaciverdiyev55@gmail.com"
//        ]);
    }
}
