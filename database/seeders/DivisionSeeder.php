<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['Mobile Apps', 'QA', 'Full Stack', 'Backend', 'Frontend', 'UI/UX Designer'];

        foreach ($names as $name) {
            Division::create([
                'name' => $name
            ]);
        }
    }
}
