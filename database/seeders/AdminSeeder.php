<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'id' => Str::uuid(),
            'name' => 'Administrator',
            'username' => 'admin',
            'phone' => '085720627537',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('pastibisa'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
