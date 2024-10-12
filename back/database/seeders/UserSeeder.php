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
        'type' => 0,
        'course_id' => null,
        'company_id' => 1,
        'email' => 'nakao.kaito0418@gmail.com',
        'first_password' => 'Tanimiti4221',
        'ip_address' => null,
    ]);

    User::create([
        'name' => '海斗',
        'password' => Hash::make('Tanimiti4221'),
        'type' => 3,
        'course_id' => null,
        'company_id' => 1,
        'email' => 'kaito@gmail.com',
        'first_password' => 'Tanimiti4221',
        'ip_address' => null,
    ]);
}

}
