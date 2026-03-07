<?php

namespace Database\Seeders; # namespace 기준으로 Seeder 실행

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'cyy3661@naver.com',
            'password' => Hash::make('admin123'), //hash로 암호화가 초기값부터 지정이 된다고????????????????????????????????
        ]);

        User::create([
            'name' => 'User1',
            'email' => 'user1@test.com',
            'password' => Hash::make('password1'),
        ]);


        User::create([
            'name' => 'User2',
            'email' => 'user2@test.com',
            'password' => Hash::make('password2'),

        ]);
    }
}
