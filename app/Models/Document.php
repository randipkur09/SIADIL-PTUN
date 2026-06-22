<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_file',
        'deskripsi',
        'category_id',
        'file_path',
        'file_type',
        'file_size',
        'uploaded_by',
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

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }

    public function getFileIconAttribute(): string
    {
        return match (strtolower($this->file_type)) {
            'pdf'           => 'bi-file-earmark-pdf text-danger',
            'doc', 'docx'   => 'bi-file-earmark-word text-primary',
            'xls', 'xlsx'   => 'bi-file-earmark-excel text-success',
            'jpg', 'jpeg',
            'png'           => 'bi-file-earmark-image text-warning',
            default         => 'bi-file-earmark text-secondary',
        };
    }
}
