<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Document;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks to safely truncate
        Schema::disableForeignKeyConstraints();
        Document::truncate(); // We must truncate documents because they rely on categories/subcategories
        SubCategory::truncate();
        Category::truncate();
        Schema::enableForeignKeyConstraints();

        $structure = [
            [
                'nama' => 'Laporan',
                'icon' => 'bi-folder',
                'sub_categories' => [
                    'Laporan Hawasbid',
                    'Laporan Pengawasan PTSP',
                    'Laporan Bulanan',
                    'Laporan Monev-Monev',
                    'Laporan Pengawasan Eksternal',
                    'Laporan Monev Pengembangan Kompetensi'
                ]
            ],
            [
                'nama' => 'Notulen',
                'icon' => 'bi-journal-text',
                'sub_categories' => [
                    'Rapat Evaluasi Bulanan',
                    'Rapat Internal Kepaniteraan',
                    'Rapat Internal Kesekretariatan',
                    'Rapat Struktural',
                    'Rapat dengan Pihak Eksternal',
                    'Rapat Internal HATIM',
                    'Rapat ZI',
                    'Rapat Lain-Lain'
                ]
            ],
            [
                'nama' => 'Surat Keputusan (SK) & SKP',
                'icon' => 'bi-file-earmark-ruled',
                'sub_categories' => [
                    'SK (Surat Keputusan)',
                    'SKP'
                ]
            ],
            [
                'nama' => 'Dokumen SAKIP & SOP',
                'icon' => 'bi-clipboard-data',
                'sub_categories' => [
                    'LAKIP',
                    'Renstra',
                    'IKU',
                    'SOP (Standard Operating Procedure)'
                ]
            ],
            [
                'nama' => 'Surat Tugas',
                'icon' => 'bi-envelope-paper',
                'sub_categories' => [
                    'Diklat/Bimtek',
                    'Dinas Luar Konsultasi',
                    'Penunjukan Plh./Plt.'
                ]
            ],
            [
                'nama' => 'Surat Keluar',
                'icon' => 'bi-send'
            ],
            [
                'nama' => 'Undangan',
                'icon' => 'bi-envelope-open'
            ]
        ];

        foreach ($structure as $item) {
            $category = Category::create([
                'nama' => $item['nama'],
                'icon' => $item['icon']
            ]);

            if (isset($item['sub_categories'])) {
                foreach ($item['sub_categories'] as $subName) {
                    SubCategory::create([
                        'category_id' => $category->id,
                        'nama' => $subName
                    ]);
                }
            }
        }
    }
}
