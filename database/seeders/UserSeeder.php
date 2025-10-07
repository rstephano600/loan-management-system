<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
public function run(): void
    {
        User::create([
            'username' => 'NuruRobert',
            'email' => 'admin@loanms.com',
            'phone' => '0657856790',
            'password' => Hash::make('arBifamadmin=4321'),
            'role' => 'admin',
        ]);
    }
}
