<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsCategory;

class NewsCategorySeeder extends Seeder
{
    /**
     * Jalankan seed database.
     */
    public function run(): void
    {
        $categories = [
            'Berita Terkini',
            'Pemerintahan',
            'Layanan Publik',
            'Agenda Kegiatan',
            'Regulasi dan Peraturan',
            'Potensi dan Profil Wilayah',
            'Pembangunan dan Infrastruktur',
            'Kesejahteraan Sosial',
            'Lingkungan Hidup',
            'Kegiatan Keagamaan dan Budaya',
            'Karang Taruna dan Kegiatan Pemuda',
            'UMKM dan Ekonomi Kreatif',
            'Informasi Kesehatan Masyarakat',
            'Galeri Foto dan Video',
            'Hubungi Kami'
        ];

        foreach ($categories as $category) {
            NewsCategory::create(['name' => $category]);
        }
    }
}
