<?php

use App\Http\Controllers\HomepageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogCommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\FarmerStoryController as AdminFarmerStoryController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;


Route::get('/', [HomepageController::class, 'home'])->name('home');

// This route will call the index method in your ProductController
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Blog routes
Route::redirect('/blog', '/blogs', 301); // consistency redirect (singular -> plural)
Route::get('/blogs', [BlogController::class, 'index'])->name('blog-list');
Route::get('/blog/{slug?}', [BlogController::class, 'show'])->name('blog-details');
Route::post('/blog/{blog}/comments', [BlogCommentController::class, 'store'])->name('blog.comments.store')->middleware('throttle:5,1');

// Contact routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Static pages
Route::view('/about-us', 'about-us')->name('about-us');

// Admin routes” requires login, verified email, and admin role
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/blogs', [AdminBlogController::class, 'index'])->name('blogs.index');
    Route::get('/blogs/create', [AdminBlogController::class, 'create'])->name('blogs.create');
    Route::post('/blogs', [AdminBlogController::class, 'store'])->name('blogs.store');
    Route::get('/blogs/{blog}/edit', [AdminBlogController::class, 'edit'])->name('blogs.edit');
    Route::put('/blogs/{blog}', [AdminBlogController::class, 'update'])->name('blogs.update');
    Route::delete('/blogs/{blog}', [AdminBlogController::class, 'destroy'])->name('blogs.destroy');

    Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{message}', [AdminContactController::class, 'show'])->name('contacts.show');
    Route::patch('/contacts/{message}/status', [AdminContactController::class, 'updateStatus'])->name('contacts.update-status');
    Route::delete('/contacts/{message}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');

    // Farmer Stories routes
    Route::get('/farmer-stories',                              [AdminFarmerStoryController::class, 'index'])->name('farmer-stories.index');
    Route::get('/farmer-stories/create',                       [AdminFarmerStoryController::class, 'create'])->name('farmer-stories.create');
    Route::post('/farmer-stories',                             [AdminFarmerStoryController::class, 'store'])->name('farmer-stories.store');
    Route::get('/farmer-stories/{farmerStory}/edit',           [AdminFarmerStoryController::class, 'edit'])->name('farmer-stories.edit');
    Route::put('/farmer-stories/{farmerStory}',                [AdminFarmerStoryController::class, 'update'])->name('farmer-stories.update');
    Route::delete('/farmer-stories/{farmerStory}',             [AdminFarmerStoryController::class, 'destroy'])->name('farmer-stories.destroy');

    // Site settings (contact details)
    Route::get('/settings',  [AdminSettingsController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
});

require __DIR__ . '/settings.php';
