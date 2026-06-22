<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Download;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function download($id)
    {
        $document = Document::findOrFail($id);

        // Catat riwayat download
        Download::create([
            'document_id'   => $document->id,
            'user_id'       => Auth::id(),
            'downloaded_at' => now(),
        ]);

        $filePath = storage_path('app/public/' . $document->file_path);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server.');
        }

        return response()->download($filePath, $document->nama_file . '.' . $document->file_type);
    }
}
