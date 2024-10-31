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
            'name' => '中尾 渓斗',
            'password' => Hash::make('Tanimiti4221'),
            'type' => 1,
            'course_id' => null,
            'company_id' => 1,
            'email' => 'nakao.keito0418@gmail.com',
            'first_password' => 'Tanimiti4221',
            'ip_address' => null,
        ]);

        User::create([
            'name' => '福品 悠翔',
            'password' => Hash::make('Tanimiti4221'),
            'type' => 3,
            'course_id' => null,
            'company_id' => 1,
            'email' => 'hukusina@gmail.com',
            'first_password' => 'Tanimiti4221',
            'ip_address' => null,
        ]);
    }

}