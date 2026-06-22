<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\User\DocumentController as UserDocumentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke login
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.documents.index');
    }
    return redirect()->route('login');
});

// Redirect /dashboard berdasarkan role (digunakan oleh RedirectIfAuthenticated)
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.documents.index');
})->middleware('auth');

// ─── Authentication ───────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Documents
    Route::get('/documents', [AdminDocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [AdminDocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [AdminDocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [AdminDocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/edit', [AdminDocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [AdminDocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [AdminDocumentController::class, 'destroy'])->name('documents.destroy');
    Route::post('/documents/{id}/restore', [AdminDocumentController::class, 'restore'])->name('documents.restore');
    Route::delete('/documents/{id}/force-delete', [AdminDocumentController::class, 'forceDelete'])->name('documents.force-delete');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// ─── User Routes ──────────────────────────────────────────────────────────────
Route::prefix('documents')->name('user.documents.')->middleware(['auth', 'role:user'])->group(function () {
    Route::get('/', [UserDocumentController::class, 'index'])->name('index');
    Route::get('/{document}', [UserDocumentController::class, 'show'])->name('show');
});

// ─── Download (Both Roles) ────────────────────────────────────────────────────
Route::get('/download/{id}', [DownloadController::class, 'download'])->name('download')->middleware('auth');
