<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BloodGroup;

class BloodGroupSeeder extends Seeder
{
    public function run(): void
    {
        $bloodGroups = [
            'A',
            'B',
            'AB',
            'O'
        ];

        foreach ($bloodGroups as $bloodGroup) {
            BloodGroup::firstOrCreate(['name' => $bloodGroup]);
        }
    }
}
