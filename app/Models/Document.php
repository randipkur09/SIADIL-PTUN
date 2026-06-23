<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'judul',
        'nomor_dokumen',
        'tanggal',
        'category_id',
        'subcategory_id',
        'nama_file',
        'path_file',
        'ukuran_file',
        'ekstensi',
        'keterangan',
        'user_id',
        'room_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    public function room()
    {
        return $this->belongsTo(User::class, 'room_id');
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->ukuran_file;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }

    public function getFileIconAttribute(): string
    {
        return match (strtolower($this->ekstensi)) {
            'pdf'           => 'bi-file-earmark-pdf text-danger',
            'doc', 'docx'   => 'bi-file-earmark-word text-primary',
            'xls', 'xlsx'   => 'bi-file-earmark-excel text-success',
            'jpg', 'jpeg',
            'png'           => 'bi-file-earmark-image text-warning',
            default         => 'bi-file-earmark text-secondary',
        };
    }
}
