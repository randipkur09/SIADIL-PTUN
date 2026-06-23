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
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('nomor_dokumen', 'like', "%{$search}%")
                  ->orWhere('nama_file', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('subcategory_id')) {
            $query->where('subcategory_id', $request->subcategory_id);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
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
            'judul'          => 'required|string|max:255',
            'nomor_dokumen'  => 'nullable|string|max:255',
            'tanggal'        => 'nullable|date',
            'keterangan'     => 'nullable|string',
            'category_id'    => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:sub_categories,id',
            'file'           => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ], [
            'judul.required'       => 'Judul dokumen wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
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
            'judul'          => $request->judul,
            'nomor_dokumen'  => $request->nomor_dokumen,
            'tanggal'        => $request->tanggal,
            'keterangan'     => $request->keterangan,
            'category_id'    => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'nama_file'      => $file->getClientOriginalName(),
            'path_file'      => $filePath,
            'ekstensi'       => $fileType,
            'ukuran_file'    => $fileSize,
            'user_id'        => Auth::id(),
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
            'judul'          => 'required|string|max:255',
            'nomor_dokumen'  => 'nullable|string|max:255',
            'tanggal'        => 'nullable|date',
            'keterangan'     => 'nullable|string',
            'category_id'    => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:sub_categories,id',
            'file'           => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ], [
            'judul.required'       => 'Judul dokumen wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'file.mimes'           => 'Format file tidak didukung.',
            'file.max'             => 'Ukuran file maksimal 10 MB.',
        ]);

        $data = [
            'judul'          => $request->judul,
            'nomor_dokumen'  => $request->nomor_dokumen,
            'tanggal'        => $request->tanggal,
            'keterangan'     => $request->keterangan,
            'category_id'    => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
        ];

        if ($request->hasFile('file')) {
            // Hapus file lama
            if ($document->path_file) {
                Storage::disk('public')->delete($document->path_file);
            }

            $file             = $request->file('file');
            $fileType         = strtolower($file->getClientOriginalExtension());
            $fileSize         = $file->getSize();
            $fileName         = time() . '_' . $file->getClientOriginalName();
            $filePath         = $file->storeAs('documents', $fileName, 'public');
            
            $data['nama_file']   = $file->getClientOriginalName();
            $data['path_file']   = $filePath;
            $data['ekstensi']    = $fileType;
            $data['ukuran_file'] = $fileSize;
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
        if ($document->path_file) {
            Storage::disk('public')->delete($document->path_file);
        }
        $document->forceDelete();
        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil dihapus permanen.');
    }
}
