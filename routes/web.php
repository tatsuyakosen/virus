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

Auth::routes();


//表示
Route::get('/product', [App\Http\Controllers\ProductController::class, 'showList'])->name('list');

//検索
Route::get('/search', [App\Http\Controllers\ProductController::class, 'search'])->name('search');

//新規登録に遷移
Route::get('/new', [App\Http\Controllers\ProductController::class, 'new'])->name('new');

//詳細
Route::get('/info/{id?}',[App\Http\Controllers\ProductController::class,'show'])->name('show');

//削除
Route::post('/delete/{id}', [ProductController::class, 'delete'])->name('delete');

//商品新規登録画面
//登録
Route::post('/updateProduct',[App\Http\Controllers\ProductController::class, 'updateProduct'])->name('updateProduct');

//商品情報詳細画面
Route::get('/showDetail/{id}', [App\Http\Controllers\ProductController::class, 'showDetail'])->name('showDetail');

//更新(編集も作成)
Route::patch('/update/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('update');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

