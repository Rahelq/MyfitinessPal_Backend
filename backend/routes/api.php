<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FoodDiaryController;
use App\Http\Controllers\Api\QuickFoodEntryController;
use App\Http\Controllers\Api\WaterEntryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Food Diary Routes 
Route::get('/foods', [FoodDiaryController::class, 'searchFoods']);       // search food items
Route::get('/diary', [FoodDiaryController::class, 'index']);             // get user diary
Route::post('/diary', [FoodDiaryController::class, 'store']);            // add food to diary
Route::delete('/diary/{id}', [FoodDiaryController::class, 'destroy']);   // delete food entry
Route::get('/daily-summary', [FoodDiaryController::class, 'dailySummary']); // nutrition summary(food + quick foods)
// Quick Food Routes
Route::get('/quick-foods', [QuickFoodEntryController::class, 'index']);     // list quick foods
Route::post('/quick-foods', [QuickFoodEntryController::class, 'store']);    // add quick entry
Route::delete('/quick-foods/{id}', [QuickFoodEntryController::class, 'destroy']); // delete quick entry

Route::get('/water', [WaterEntryController::class, 'index']);
Route::post('/water', [WaterEntryController::class, 'store']);
Route::delete('/water/{id}', [WaterEntryController::class, 'destroy']);
