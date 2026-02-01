<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->firstOrCreate([
            'email' => 'admin@sorsu.edu.ph',
        ], [
            'name' => 'SORSU Admin',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $subjects = [
            ['code' => 'CS101', 'title' => 'Intro to Computing'],
            ['code' => 'ENG201', 'title' => 'Academic Writing'],
            ['code' => 'MATH110', 'title' => 'College Algebra'],
        ];

        foreach ($subjects as $subject) {
            Subject::query()->firstOrCreate(['code' => $subject['code']], $subject);
        }
    }
}
