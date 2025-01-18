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
        $admin = User::create([
            "name" => "中尾スタッフ",
            "password" => Hash::make("Kate0418"),
            "type" => "admin",
            "company_id" => 1,
            "email" => "admin.nakao@example.com",
            "first_password" => "Kate0418",
        ]);

        $teacher = User::create([
            "name" => "中尾先生",
            "password" => Hash::make("Kate0418"),
            "type" => "teacher",
            "company_id" => 1,
            "email" => "teacher.nakao@example.com",
            "first_password" => "Kate0418",
        ]);

        $student =  User::create([
            "name" => "中尾生徒",
            "password" => Hash::make("Kate0418"),
            "type" => "student",
            "company_id" => 1,
            "email" => "student.nakao@example.com",
            "first_password" => "Kate0418",
        ]);

        $student->student()->create([
            "course_id" => null,
            "master_grade_id" => 1,
            "master_year_id" => 1,
        ]);
    }
}
