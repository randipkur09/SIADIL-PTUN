<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nama_ruangan' => 'Kepaniteraan',  'deskripsi' => 'Arsip dokumen kepaniteraan pengadilan'],
            ['nama_ruangan' => 'Keuangan',      'deskripsi' => 'Arsip dokumen keuangan dan anggaran'],
            ['nama_ruangan' => 'Kepegawaian',   'deskripsi' => 'Arsip dokumen kepegawaian dan SDM'],
            ['nama_ruangan' => 'Umum',          'deskripsi' => 'Arsip dokumen umum dan administrasi'],
            ['nama_ruangan' => 'Hakim',         'deskripsi' => 'Arsip dokumen majelis hakim'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
