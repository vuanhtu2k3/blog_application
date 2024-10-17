<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/posts/index', [PostController::class, 'index'])->name('post.index');
Route::get('/posts/create', [PostController::class, 'create'])->name('post.create');
Route::post('/posts/store', [PostController::class, 'store'])->name('post.store');
Route::get('/posts/edit/{id}', [PostController::class, 'edit'])->name('post.edit');
Route::post('/posts/update/{id}', [PostController::class, 'update'])->name('post.update');
Route::delete('/posts/delete/{id}', [PostController::class, 'destroy'])->name('post.delete');
