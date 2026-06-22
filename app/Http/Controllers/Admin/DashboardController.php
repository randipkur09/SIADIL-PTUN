<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Document;
use App\Models\Download;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDocuments = Document::count();
        $totalCategories = Category::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalDownloads = Download::count();

        $recentDocuments = Document::with(['category', 'uploader'])
            ->latest()
            ->take(5)
            ->get();

        $downloadsByCategory = Category::withCount(['documents as download_count' => function ($q) {
            $q->join('downloads', 'documents.id', '=', 'downloads.document_id');
        }])->get();

        $recentDownloads = Download::with(['document', 'user'])
            ->latest('downloaded_at')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalDocuments',
            'totalCategories',
            'totalUsers',
            'totalDownloads',
            'recentDocuments',
            'downloadsByCategory',
            'recentDownloads'
        ));
    }
}
