<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\WelcomeController;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::pattern('id','[0-9]+'); // artinya ketika ada parameter {id}, maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin'])->name('login.aksi');
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('register', [AuthController::class, 'register'])->name('register');
 Route::post('register', [AuthController::class, 'postregister']);




Route::middleware(['auth'])->group(function(){ 
    // artinya semua route di dalam group ini harus login dulu

    // masukkan semua route yang perlu autentikasi di sini
    
    Route::get('/', [WelcomeController::class, 'index']);

    
    
    Route::middleware(['authorize:ADM'])->group(function () {
        Route::get('/user', [UserController::class, 'index']);       // Menampilkan halaman awal user
        Route::post('/user/list', [UserController::class, 'list']);   // Menampilkan data user dalam bentuk json untuk datatables
        Route::get('/user/create', [UserController::class, 'create']); // Menampilkan halaman form tambah user
        Route::post('/user/', [UserController::class, 'store']);       // Menyimpan data user baru
    
        Route::get('/user/create_ajax', [UserController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/user/ajax', [UserController::class, 'store_ajax']);        // Menyimpan data user baru Ajax
    
        Route::get('/user/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // Menampilkan halaman form edit user Ajax
        Route::put('/user/{id}/update_ajax', [UserController::class, 'update_ajax']); // Menyimpan perubahan data user Ajax
    
        Route::get('/user/{id}/show_ajax', [UserController::class, 'show_ajax']);
    
        Route::get('/user/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete user Ajax
        Route::delete('/user/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Untuk hapus data user Ajax
    
    
        Route::get('/user/{id}', [UserController::class, 'show']);     // Menampilkan detail user
        Route::get('/user/{id}/edit', [UserController::class, 'edit']); // Menampilkan halaman form edit user
        Route::put('/user/{id}', [UserController::class, 'update']);    // Menyimpan perubahan data user
        Route::delete('/user/{id}', [UserController::class, 'destroy']); // Menghapus data user
        Route::get('/user/import', [UserController::class, 'import']); // ajax form uplaod excel
        Route::post('/user/import_ajax', [UserController::class, 'import_ajax']); // ajax import excel
        route::get('/user/export_excel', [UserController::class, 'export_excel']); // ajax form uplaod excel
        Route::get('/user/export_pdf', [UserController::class, 'export_pdf']); // ajax form uplaod excel
    });
    

    // artinya semua route di dalam group ini harus punya role ADM (Administrator)
    Route::middleware(['authorize:ADM'])->group(function () {
        Route::get('/level', [LevelController::class, 'index']);
        Route::post('/level/list', [LevelController::class, 'list']); // untuk list json datatables
        Route::get('/level/create', [LevelController::class, 'create']);
        Route::post('/level', [LevelController::class, 'store']);
        Route::get('/level/{id}/edit', [LevelController::class, 'edit']); // untuk tampilkan form edit
        Route::put('/level/{id}', [LevelController::class, 'update']); // untuk proses update data
        Route::delete('/level/{id}', [LevelController::class, 'destroy']); // untuk proses hapus data
        Route::get('/level/import', [LevelController::class, 'import']); // ajax form uplaod excel
        Route::post('/level/import_ajax', [LevelController::class, 'import_ajax']); // ajax import excel
        route::get('/level/export_excel', [LevelController::class, 'export_excel']); // ajax form uplaod excel
        Route::get('/level/export_pdf', [levelController::class, 'export_pdf']); // ajax form uplaod excel
    });
    
    
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
        Route::get('/kategori', [KategoriController::class, 'index']); // menampilkan halaman awal Kategori
        Route::post('/kategori/list', [KategoriController::class, 'list']); // menampilkan data Kategori dalam bentuk json untuk datatables
        Route::get('/kategori/create', [KategoriController::class, 'create']); // menampilkan halaman form tambah Kategori
        Route::post('/kategori', [KategoriController::class, 'store']); // menyimpan data Kategori baru
    
        Route::get('/kategori/create_ajax', [KategoriController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/kategori/ajax', [KategoriController::class, 'store_ajax']);        // Menyimpan data user baru Ajax
    
        Route::get('/kategori/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); // Menampilkan halaman form edit user Ajax
        Route::put('/kategori/{id}/update_ajax', [KategoriController::class, 'update_ajax']); // Menyimpan perubahan data user Ajax
    
        Route::get('/kategori/{id}/show_ajax', [KategoriController::class, 'show_ajax']);
    
        Route::get('/kategori/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete user Ajax
        Route::delete('/kategori/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); // Untuk hapus data user Ajax
        
        Route::get('/kategori/{id}', [KategoriController::class, 'show']); // menampilkan detail Kategori
        Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit']); // menampilkan halaman form edit Kategori
        Route::put('/kategori/{id}', [KategoriController::class, 'update']); // menyimpan perubahan data Kategori
        Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']); // menghapus data user
        Route::get('/kategori/import', [KategoriController::class, 'import']); // ajax form uplaod excel
        Route::post('/kategori/import_ajax', [KategoriController::class, 'import_ajax']); // ajax import excel
        route::get('/kategori/export_excel', [KategoriController::class, 'export_excel']); // ajax form uplaod excel
        Route::get('/kategori/export_pdf', [KategoriController::class, 'export_pdf']); // ajax form uplaod excel
    });
    
    Route::middleware(['authorize:ADM,MNG'])->group(function() {
        Route::get('/barang', [BarangController::class, 'index']); // menampilkan halaman awal Barang
        Route::post('/barang/list', [BarangController::class, 'list']); // menampilkan data Barang dalam bentuk json untuk datatables
        Route::get('/barang/create', [BarangController::class, 'create']); // menampilkan halaman form tambah Barang
        Route::post('/barang', [BarangController::class, 'store']); // menyimpan data Barang baru
    
        Route::get('/barang/create_ajax', [BarangController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/barang/store_ajax', [BarangController::class, 'store_ajax']);        // Menyimpan data user baru Ajax
    
        Route::get('/barang/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); // Menampilkan halaman form edit user Ajax
        Route::put('/barang/{id}/update_ajax', [BarangController::class, 'update_ajax']); // Menyimpan perubahan data user Ajax
    
        Route::get('/barang/{id}/show_ajax', [BarangController::class, 'show_ajax']);
    
        Route::get('/barang/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete user Ajax
        Route::delete('/barang/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); // Untuk hapus data user Ajax
    
        Route::get('/barang/{id}', [BarangController::class, 'show']); // menampilkan detail Barang
        Route::get('/barang/{id}/edit', [BarangController::class, 'edit']); // menampilkan halaman form edit Barang
        Route::put('/barang/{id}', [BarangController::class, 'update']); // menyimpan perubahan data Barang
        Route::delete('/barang/{id}', [BarangController::class, 'destroy']); // menghapus data user
        Route::get('/barang/import', [BarangController::class, 'import']); // ajax form uplaod excel
        Route::post('/barang/import_ajax', [BarangController::class, 'import_ajax']); // ajax import excel

        route::get('/barang/export_excel', [BarangController::class, 'export_excel']); // ajax form uplaod excel
        Route::get('/barang/export_pdf', [BarangController::class, 'export_pdf']); // ajax form uplaod excel
    });
    
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
        Route::get('/supplier', [SupplierController::class, 'index']); // menampilkan halaman awal Supplier
        Route::post('/supplier/list', [SupplierController::class, 'list']); // menampilkan data Supplier dalam bentuk json untuk datatables
        Route::get('/supplier/create', [SupplierController::class, 'create']); // menampilkan halaman form tambah Supplier
        Route::post('/supplier', [SupplierController::class, 'store']); // menyimpan data Supplier baru
    
        Route::get('/supplier/create_ajax', [SupplierController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/supplier/ajax', [SupplierController::class, 'store_ajax']);        // Menyimpan data user baru Ajax
    
        Route::get('/supplier/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); // Menampilkan halaman form edit user Ajax
        Route::put('/supplier/{id}/update_ajax', [SupplierController::class, 'update_ajax']); // Menyimpan perubahan data user Ajax
    
        Route::get('/supplier/{id}/show_ajax', [SupplierController::class, 'show_ajax']);
    
        Route::get('/supplier/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete user Ajax
        Route::delete('/supplier/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); // Untuk hapus data user Ajax
    
        Route::get('/supplier/{id}', [SupplierController::class, 'show']); // menampilkan detail Supplier
        Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit']); // menampilkan halaman form edit Supplier
        Route::put('/supplier/{id}', [SupplierController::class, 'update']); // menyimpan perubahan data Supplier
        Route::delete('/supplier/{id}', [SupplierController::class, 'destroy']); // menghapus data user
        Route::get('/supplier/import', [SupplierController::class, 'import']); // ajax form uplaod excel
        Route::post('/supplier/import_ajax', [SupplierController::class, 'import_ajax']); // ajax import excel
        route::get('/supplier/export_excel', [SupplierController::class, 'export_excel']); // ajax form uplaod excel
        Route::get('/supplier/export_pdf', [SupplierController::class, 'export_pdf']); // ajax form uplaod excel
    });

    // Route profile

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update-foto', [ProfileController::class, 'updateFoto'])->name('profile.updateFoto');
    

});
