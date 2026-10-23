<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PromoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| REST API Mutiara Jaya  (semua url diawali /api)
|--------------------------------------------------------------------------
| Cek daftar route dengan: php artisan route:list --path=api
*/

// ---------- Auth routes (publik) ----------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ---------- Data menu (publik, supaya bisa dipakai frontend tanpa login) ----------
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/promos', [PromoController::class, 'index']);

// ---------- Wajib token Sanctum (header: Authorization: Bearer <token>) ----------
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // pesanan milik user yang login
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    // CRUD menu & kategori: khusus admin (token milik akun admin)
    Route::middleware('admin')->group(function () {
        Route::apiResource('products', ProductController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('categories', CategoryController::class)->only(['store', 'update', 'destroy']);
    });
});

/*
 | CATATAN: pada modul BAB III seluruh endpoint products dikunci dengan:
 |    Route::apiResource('products', ProductController::class)->middleware('auth:sanctum');
 | Kalau dosen meminta GET /api/products juga wajib token (401 tanpa token),
 | hapus dua baris GET /products di atas lalu pakai baris apiResource tersebut.
 */
