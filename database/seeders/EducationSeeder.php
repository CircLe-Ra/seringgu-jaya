<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Education;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        $educations = [
            'TIDAK / BELUM SEKOLAH',
            'BELUM TAMAT SD/SEDERAJAT',
            'TAMAT SD / SEDERAJAT',
            'SLTP/SEDERAJAT',
            'SLTA / SEDERAJAT',
            'DIPLOMA I / II',
            'AKADEMI/ DIPLOMA III/S. MUDA',
            'DIPLOMA IV/ STRATA I',
            'STRATA II',
            'STRATA III'
        ];

        foreach ($educations as $education) {
            Education::firstOrCreate(['name' => $education]);
        }
    }
}
