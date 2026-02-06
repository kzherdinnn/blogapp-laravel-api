<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

// Route utama
Route::get('/', function () {
    return view('welcome');
});

// Mengaktifkan rute untuk otentikasi (login, register, dll)
Auth::routes();

// Rute menuju home setelah login
Route::get('/home', [HomeController::class, 'index'])->name('home');

// DEBUG ROUTE - HAPUS NANTI
Route::get('/debug-images', function() {
    $results = [];

    // 1. Cek Folder Public Storage Link
    $publicStorage = public_path('storage');
    $results['public_storage_path'] = $publicStorage;
    $results['public_storage_exists'] = file_exists($publicStorage);
    $results['public_storage_is_link'] = is_link($publicStorage);
    
    // 2. Cek Real Storage Path 
    $storagePath = storage_path('app/public/posts');
    $results['real_storage_path_posts'] = $storagePath;
    $results['real_storage_exists'] = file_exists($storagePath);
    
    // 3. List File di Storage Posts
    if (file_exists($storagePath)) {
        $files = scandir($storagePath);
        $results['files_in_posts'] = array_values(array_diff($files, ['.', '..']));
    } else {
        $results['files_in_posts'] = 'Folder not found';
    }

    // 4. Cek Permission
    $results['storage_perms'] = substr(sprintf('%o', fileperms(storage_path('app/public'))), -4);

    return response()->json($results);
});
