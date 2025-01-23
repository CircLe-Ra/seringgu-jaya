<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employment;

class EmploymentSeeder extends Seeder
{
    public function run(): void
    {
        $employments = [
            'Pegawai Negeri Sipil (PNS)',
            'Tentara Nasional Indonesia (TNI)',
            'Kepolisian Republik Indonesia (POLRI)',
            'Petani',
            'Peternak',
            'Nelayan',
            'Karyawan swasta',
            'Karyawan BUMN',
            'Karyawan BUMD',
            'Buruh harian lepas'
        ];

        foreach ($employments as $employment) {
            Employment::firstOrCreate(['name' => $employment]);
        }
    }
}
