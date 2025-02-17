<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SubscriberController;

// THEME ROUTE
Route::controller(ThemeController::class)->name('theme.')->group(function (){
    Route::get('/', 'index')->name('index');
    Route::get('/category/{id}', 'category')->name('category');
    Route::get('/contact', 'contact')->name('contact');
});

// SUBSCRIBER STORE ROUTE
Route::post('subscriber/store', [SubscriberController::class, 'store'])->name('subscriber.store');

// CONTACT STORE ROUTE
Route::post('contact/store', [ContactController::class, 'store'])->name('contact.store');

// BLOG ROUTE
Route::get('/my-blogs', [BlogController::class, 'myBlogs'])->name('blogs.my-blogs');
Route::resource('blogs', BlogController::class)->except('index');

// COMMENT ROUTE
Route::post('comments/store', [CommentController::class, 'store'])->name('comments.store');

require __DIR__.'/auth.php';



