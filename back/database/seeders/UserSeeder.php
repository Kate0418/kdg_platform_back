<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "name" => "中尾 渓斗",
            "password" => Hash::make("Tanimiti4221"),
            "type" => 1,
            "company_id" => 1,
            "email" => "nakao.keito0418@gmail.com",
            "first_password" => "Kate0418",
        ]);
    }
}
