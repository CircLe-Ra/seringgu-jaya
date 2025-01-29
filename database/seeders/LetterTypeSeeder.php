<?php

namespace Database\Seeders;

use App\Models\LetterType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LetterTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Surat Keterangan Domisili Penduduk',
            'Surat Keterangan Kematian',
            'Surat Keterangan Kelahiran',
            'Surat Keterangan Pindah',
            'Surat Keterangan Izin Usaha',
            'Surat Keterangan Tidak Mampu',
            'Surat Keterangan Belum Menikah',
            'Surat Keterangan Pindah Domisili',
            'Surat Keterangan Menikah',
            'Surat Keterangan Warisan',
            'Surat Keterangan Usaha',
            'Surat Pertanahan',
            'Surat Pensiun',
        ];

        foreach ($types as $type) {
            LetterType::create(['name' => $type]);
        }
    }
}
