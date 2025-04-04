<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|-------------------------------------------------------------------------- 
| Web Routes
|-------------------------------------------------------------------------- 
| Here is where you can register web routes for your application.
| Routes are loaded by the RouteServiceProvider within a group containing 
| the "web" middleware group. Now create something great!
| 
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();  // 認証用ルートを追加

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// 商品関連のルートを個別に定義
Route::get('products', [ProductController::class, 'index'])->name('products.index'); // 商品一覧
Route::get('products/create', [ProductController::class, 'create'])->name('products.create'); // 商品登録画面
Route::post('products', [ProductController::class, 'store'])->name('products.store'); // 商品登録処理
Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show'); // 商品詳細画面
Route::get('products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit'); // 商品編集画面
Route::put('products/{id}', [ProductController::class, 'update'])->name('products.update'); // 商品更新処理
Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('products.destroy'); // 商品削除処理
