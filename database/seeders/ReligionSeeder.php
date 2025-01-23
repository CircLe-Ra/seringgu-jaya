<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Religion;

class ReligionSeeder extends Seeder
{
    public function run(): void
    {
        $religions = [
            'Islam',
            'Kristen Protestan',
            'Kristen Katholik',
            'Hindu',
            'Buddha',
            'Konghucu'
        ];

        foreach ($religions as $religion) {
            Religion::firstOrCreate(['name' => $religion]);
        }
    }
}
