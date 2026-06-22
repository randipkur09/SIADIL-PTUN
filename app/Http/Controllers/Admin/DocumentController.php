<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with(['category', 'uploader'])->withTrashed();

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

        if ($request->filled('status')) {
            if ($request->status === 'deleted') {
                $query->onlyTrashed();
            } elseif ($request->status === 'active') {
                $query->withoutTrashed();
            }
        }

        $documents = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.documents.index', compact('documents', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.documents.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_file'   => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'file'        => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ], [
            'nama_file.required'   => 'Nama dokumen wajib diisi.',
            'category_id.required' => 'Kategori ruangan wajib dipilih.',
            'file.required'        => 'File dokumen wajib diunggah.',
            'file.mimes'           => 'Format file tidak didukung. Gunakan PDF, DOC, DOCX, XLS, XLSX, JPG, atau PNG.',
            'file.max'             => 'Ukuran file maksimal 10 MB.',
        ]);

        $file     = $request->file('file');
        $fileType = strtolower($file->getClientOriginalExtension());
        $fileSize = $file->getSize();
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents', $fileName, 'public');

        Document::create([
            'nama_file'   => $request->nama_file,
            'deskripsi'   => $request->deskripsi,
            'category_id' => $request->category_id,
            'file_path'   => $filePath,
            'file_type'   => $fileType,
            'file_size'   => $fileSize,
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil diunggah.');
    }

    public function show(Document $document)
    {
        $document->load(['category', 'uploader', 'downloads.user']);
        return view('admin.documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        $categories = Category::all();
        return view('admin.documents.edit', compact('document', 'categories'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'nama_file'   => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ], [
            'nama_file.required'   => 'Nama dokumen wajib diisi.',
            'category_id.required' => 'Kategori ruangan wajib dipilih.',
            'file.mimes'           => 'Format file tidak didukung.',
            'file.max'             => 'Ukuran file maksimal 10 MB.',
        ]);

        $data = [
            'nama_file'   => $request->nama_file,
            'deskripsi'   => $request->deskripsi,
            'category_id' => $request->category_id,
        ];

        if ($request->hasFile('file')) {
            // Hapus file lama
            Storage::disk('public')->delete($document->file_path);

            $file             = $request->file('file');
            $fileType         = strtolower($file->getClientOriginalExtension());
            $fileSize         = $file->getSize();
            $fileName         = time() . '_' . $file->getClientOriginalName();
            $filePath         = $file->storeAs('documents', $fileName, 'public');
            $data['file_path'] = $filePath;
            $data['file_type'] = $fileType;
            $data['file_size'] = $fileSize;
        }

        $document->update($data);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Document $document)
    {
        $document->delete(); // Soft delete
        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    public function restore($id)
    {
        $document = Document::onlyTrashed()->findOrFail($id);
        $document->restore();
        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $document = Document::onlyTrashed()->findOrFail($id);
        Storage::disk('public')->delete($document->file_path);
        $document->forceDelete();
        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil dihapus permanen.');
    }
}
