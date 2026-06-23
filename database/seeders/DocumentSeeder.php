<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->command->warn('Admin user not found, skipping Document seeding.');
            return;
        }

        $categories = Category::with('subCategories')->get();

        foreach ($categories as $category) {
            // If category has subcategories, we seed into each subcategory
            if ($category->subCategories->count() > 0) {
                foreach ($category->subCategories as $subCategory) {
                    $this->createDummyDocuments($admin->id, $category->id, $subCategory->id, $subCategory->nama);
                }
            } else {
                // Seed directly into the category
                $this->createDummyDocuments($admin->id, $category->id, null, $category->nama);
            }
        }
    }

    private function createDummyDocuments($userId, $categoryId, $subCategoryId, $folderName)
    {
        // Create 2-3 dummy documents per folder
        $count = rand(2, 3);

        for ($i = 1; $i <= $count; $i++) {
            $ext = ['pdf', 'docx', 'xlsx'][rand(0, 2)];
            $size = rand(500000, 5000000); // 500KB to 5MB

            Document::create([
                'judul' => "Dokumen Contoh " . $i . " - " . $folderName,
                'nomor_dokumen' => strtoupper(Str::random(4)) . "/PTUN/" . date('Y'),
                'tanggal' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
                'category_id' => $categoryId,
                'subcategory_id' => $subCategoryId,
                'nama_file' => Str::slug($folderName . " " . $i) . "." . $ext,
                'path_file' => 'documents/dummy_file_' . Str::random(5) . '.' . $ext,
                'ukuran_file' => $size,
                'ekstensi' => $ext,
                'keterangan' => 'Ini adalah isian data dummy dokumen untuk folder ' . $folderName,
                'user_id' => $userId,
            ]);
        }
    }
}
