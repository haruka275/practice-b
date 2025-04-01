<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();  // 認証用ルートを追加

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// 商品関連のルートをリソースコントローラで一括登録
Route::resource('products', ProductController::class);
