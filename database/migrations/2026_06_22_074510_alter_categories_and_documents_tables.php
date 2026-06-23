<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('nama_ruangan', 'nama');
            $table->string('icon')->nullable();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->renameColumn('file_path', 'path_file');
            $table->renameColumn('file_size', 'ukuran_file');
            $table->renameColumn('file_type', 'ekstensi');
            $table->renameColumn('uploaded_by', 'user_id');
            $table->renameColumn('deskripsi', 'keterangan');

            $table->string('judul')->after('id')->nullable();
            $table->string('nomor_dokumen')->after('judul')->nullable();
            $table->date('tanggal')->after('nomor_dokumen')->nullable();
            $table->foreignId('subcategory_id')->after('category_id')->nullable()->constrained('sub_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['subcategory_id']);
            $table->dropColumn(['judul', 'nomor_dokumen', 'tanggal', 'subcategory_id']);

            $table->renameColumn('path_file', 'file_path');
            $table->renameColumn('ukuran_file', 'file_size');
            $table->renameColumn('ekstensi', 'file_type');
            $table->renameColumn('user_id', 'uploaded_by');
            $table->renameColumn('keterangan', 'deskripsi');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('icon');
            $table->renameColumn('nama', 'nama_ruangan');
        });
    }
};
