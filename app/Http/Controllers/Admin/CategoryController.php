<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('documents')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:255|unique:categories,nama_ruangan',
            'deskripsi'    => 'nullable|string',
        ], [
            'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
            'nama_ruangan.unique'   => 'Nama ruangan sudah ada.',
        ]);

        Category::create($request->only('nama_ruangan', 'deskripsi'));

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori ruangan berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:255|unique:categories,nama_ruangan,' . $category->id,
            'deskripsi'    => 'nullable|string',
        ], [
            'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
            'nama_ruangan.unique'   => 'Nama ruangan sudah ada.',
        ]);

        $category->update($request->only('nama_ruangan', 'deskripsi'));

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori ruangan berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->documents()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki dokumen.');
        }
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori ruangan berhasil dihapus.');
    }
}
