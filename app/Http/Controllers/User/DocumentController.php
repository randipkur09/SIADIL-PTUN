<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with(['category', 'uploader']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_file', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $documents  = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('user.documents.index', compact('documents', 'categories'));
    }

    public function show(Document $document)
    {
        $document->load(['category', 'uploader', 'downloads']);
        return view('user.documents.show', compact('document'));
    }
}
