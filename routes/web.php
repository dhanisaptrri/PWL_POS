<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::pattern('id','[0-9]+'); // artinya ketika ada parameter {id}, maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin'])->name('login.aksi');
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);       // Menampilkan halaman awal user
    Route::post('/list', [UserController::class, 'list']);   // Menampilkan data user dalam bentuk json untuk datatables
    Route::get('/create', [UserController::class, 'create']); // Menampilkan halaman form tambah user
    Route::post('/', [UserController::class, 'store']);       // Menyimpan data user baru

    Route::get('/create_ajax', [UserController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
    Route::post('/ajax', [UserController::class, 'store_ajax']);        // Menyimpan data user baru Ajax

    Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // Menampilkan halaman form edit user Ajax
    Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // Menyimpan perubahan data user Ajax

    Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);

    Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete user Ajax
    Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Untuk hapus data user Ajax


    Route::get('/{id}', [UserController::class, 'show']);     // Menampilkan detail user
    Route::get('/{id}/edit', [UserController::class, 'edit']); // Menampilkan halaman form edit user
    Route::put('/{id}', [UserController::class, 'update']);    // Menyimpan perubahan data user
    Route::delete('/{id}', [UserController::class, 'destroy']); // Menghapus data user
});



Route::middleware(['auth'])->group(function(){ 
    // artinya semua route di dalam group ini harus login dulu

    // masukkan semua route yang perlu autentikasi di sini
    
    Route::get('/', [WelcomeController::class, 'index']);
    // route Level

    // artinya semua route di dalam group ini harus punya role ADM (Administrator)
    Route::middleware(['authorize:ADM'])->group(function () {
        Route::get('/level', [LevelController::class, 'index']);
        Route::post('/level/list', [LevelController::class, 'list']); // untuk list json datatables
        Route::get('/level/create', [LevelController::class, 'create']);
        Route::post('/level', [LevelController::class, 'store']);
        Route::get('/level/{id}/edit', [LevelController::class, 'edit']); // untuk tampilkan form edit
        Route::put('/level/{id}', [LevelController::class, 'update']); // untuk proses update data
        Route::delete('/level/{id}', [LevelController::class, 'destroy']); // untuk proses hapus data
    });
    
    
    Route::group(['prefix' => 'kategori'], function () {
        Route::get('/', [KategoriController::class, 'index']); // menampilkan halaman awal Kategori
        Route::post('/list', [KategoriController::class, 'list']); // menampilkan data Kategori dalam bentuk json untuk datatables
        Route::get('/create', [KategoriController::class, 'create']); // menampilkan halaman form tambah Kategori
        Route::post('/', [KategoriController::class, 'store']); // menyimpan data Kategori baru
    
        Route::get('/create_ajax', [KategoriController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [KategoriController::class, 'store_ajax']);        // Menyimpan data user baru Ajax
    
        Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); // Menampilkan halaman form edit user Ajax
        Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']); // Menyimpan perubahan data user Ajax
    
        Route::get('/{id}/show_ajax', [KategoriController::class, 'show_ajax']);
    
        Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete user Ajax
        Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); // Untuk hapus data user Ajax
        
        Route::get('/{id}', [KategoriController::class, 'show']); // menampilkan detail Kategori
        Route::get('/{id}/edit', [KategoriController::class, 'edit']); // menampilkan halaman form edit Kategori
        Route::put('/{id}', [KategoriController::class, 'update']); // menyimpan perubahan data Kategori
        Route::delete('/{id}', [KategoriController::class, 'destroy']); // menghapus data user
    });
    
    Route::group(['prefix' => 'barang'], function () {
        Route::get('/', [BarangController::class, 'index']); // menampilkan halaman awal Barang
        Route::post('/list', [BarangController::class, 'list']); // menampilkan data Barang dalam bentuk json untuk datatables
        Route::get('/create', [BarangController::class, 'create']); // menampilkan halaman form tambah Barang
        Route::post('/', [BarangController::class, 'store']); // menyimpan data Barang baru
    
        Route::get('/create_ajax', [BarangController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/store_ajax', [BarangController::class, 'store_ajax']);        // Menyimpan data user baru Ajax
    
        Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); // Menampilkan halaman form edit user Ajax
        Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']); // Menyimpan perubahan data user Ajax
    
        Route::get('/{id}/show_ajax', [BarangController::class, 'show_ajax']);
    
        Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete user Ajax
        Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); // Untuk hapus data user Ajax
    
        Route::get('/{id}', [BarangController::class, 'show']); // menampilkan detail Barang
        Route::get('/{id}/edit', [BarangController::class, 'edit']); // menampilkan halaman form edit Barang
        Route::put('/{id}', [BarangController::class, 'update']); // menyimpan perubahan data Barang
        Route::delete('/{id}', [BarangController::class, 'destroy']); // menghapus data user
        
    });
    
    Route::group(['prefix' => 'supplier'], function () {
        Route::get('/', [SupplierController::class, 'index']); // menampilkan halaman awal Supplier
        Route::post('/list', [SupplierController::class, 'list']); // menampilkan data Supplier dalam bentuk json untuk datatables
        Route::get('/create', [SupplierController::class, 'create']); // menampilkan halaman form tambah Supplier
        Route::post('/', [SupplierController::class, 'store']); // menyimpan data Supplier baru
    
        Route::get('/create_ajax', [SupplierController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [SupplierController::class, 'store_ajax']);        // Menyimpan data user baru Ajax
    
        Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); // Menampilkan halaman form edit user Ajax
        Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']); // Menyimpan perubahan data user Ajax
    
        Route::get('/{id}/show_ajax', [SupplierController::class, 'show_ajax']);
    
        Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete user Ajax
        Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); // Untuk hapus data user Ajax
    
        Route::get('/{id}', [SupplierController::class, 'show']); // menampilkan detail Supplier
        Route::get('/{id}/edit', [SupplierController::class, 'edit']); // menampilkan halaman form edit Supplier
        Route::put('/{id}', [SupplierController::class, 'update']); // menyimpan perubahan data Supplier
        Route::delete('/{id}', [SupplierController::class, 'destroy']); // menghapus data user
        
    });
});
