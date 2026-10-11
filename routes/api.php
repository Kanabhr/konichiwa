<?php

use App\Http\Controllers\Api\FarmerStoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Farmer Stories API Routes
|--------------------------------------------------------------------------
| All routes are prefixed with /api automatically via bootstrap/app.php
| These routes are completely separate from web.php
|
| Base URL: http://localhost:8000/api/farmer-stories
*/

// Route::prefix('farmer-stories')->name('api.farmer-stories.')->group(function () {
//     Route::get('/',                          [FarmerStoryController::class, 'index'])->name('index');
//     Route::post('/',                         [FarmerStoryController::class, 'store'])->name('store');
//     Route::get('/{farmerStory}',             [FarmerStoryController::class, 'show'])->name('show');
//     Route::post('/{farmerStory}',            [FarmerStoryController::class, 'update'])->name('update');
//     Route::delete('/{farmerStory}',          [FarmerStoryController::class, 'destroy'])->name('destroy');
// });
