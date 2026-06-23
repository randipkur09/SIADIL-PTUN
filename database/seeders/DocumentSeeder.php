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
        $rooms = User::where('role', 'user')->get();
        
        if (!$admin || $rooms->isEmpty()) {
            $this->command->warn('Admin or Room users not found, skipping Document seeding.');
            return;
        }

        $categories = Category::with('subCategories')->get();

        foreach ($rooms as $room) {
            // Pick 2 random categories for each room to keep dummy data minimal
            $randomCategories = $categories->random(2);

            foreach ($randomCategories as $category) {
                // Pick 1 random subcategory if exists, or null
                $subCategory = $category->subCategories->count() > 0 
                    ? $category->subCategories->random() 
                    : null;
                
                $subCategoryId = $subCategory ? $subCategory->id : null;
                $folderName = $subCategory ? $subCategory->nama : $category->nama;

                // Create exactly 1 doc per selected category
                $this->createDummyDocument($admin->id, $room, $category->id, $subCategoryId, $folderName);
            }
        }
    }

    private function createDummyDocument($adminId, $room, $categoryId, $subCategoryId, $folderName)
    {
        $ext = ['pdf', 'docx', 'xlsx'][rand(0, 2)];
        $size = rand(500000, 2000000); // 500KB to 2MB

        Document::create([
            'judul' => "Dokumen " . $folderName . " - " . $room->name,
            'nomor_dokumen' => strtoupper(Str::random(4)) . "/" . strtoupper(Str::slug($room->name, '')) . "/" . date('Y'),
            'tanggal' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
            'category_id' => $categoryId,
            'subcategory_id' => $subCategoryId,
            'room_id' => $room->id,
            'nama_file' => Str::slug($folderName . " " . $room->name) . "." . $ext,
            'path_file' => 'documents/dummy_file_' . Str::random(5) . '.' . $ext,
            'ukuran_file' => $size,
            'ekstensi' => $ext,
            'keterangan' => 'Ini adalah data dummy dokumen ' . $folderName . ' untuk ruangan ' . $room->name,
            'user_id' => $adminId, // Admin as uploader
        ]);
    }
}
