<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\LoginUserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterUserController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/test', function () {
//     $test = "Test Again";
//     return "<h1>".$test."</h1>";
// });

Route::view('/', 'welcome');

// Route::get('/posts',  [PostController::class, 'index']);

// Route::get('/posts/create',  [PostController::class, 'create']);

// Route::post('/posts',  [PostController::class, 'store']);

// Route::get('/posts/{id}',  [PostController::class, 'show']);

// Route::get('/posts/{id}/edit',  [PostController::class, 'edit']);
   
// Route::put('/posts/{id}', [PostController::class, 'update']);

// Route::delete('/posts/{id}', [PostController::class, 'destroy']);

Route::resource('/posts', PostController::class)->middleware('auth');
Route::middleware('auth')->group(function(){
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->can('update', 'post')->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/logout', [LoginUserController::class, 'logout'])->name('logout');

    
Route::middleware('is_admin')->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    Route::get('/admin/posts/{post}/edit', [AdminPostController::class, 'edit'])->name('admin.posts.edit');
    Route::put('/admin/posts/{post}', [AdminPostController::class, 'update'])->name('admin.posts.update');
    Route::delete('/admin/posts/{post}', [AdminPostController::class, 'destroy'])->name('admin.posts.destroy');

});
}); 

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

Route::middleware('guest')->group(function(){
    Route::get('/login', [LoginUserController::class, 'login'])->name('login');
    Route::post('/login', [LoginUserController::class,'store'])->name('login.store');
   
    Route::get('/register', [RegisterUserController::class, 'register'])->name('register');
    Route::post('/register', [RegisterUserController::class, 'store'])->name('register.store');
});

