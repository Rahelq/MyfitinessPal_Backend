<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;  
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\QuickExercisesController;
use App\Http\Controllers\CardioExercisesController;
use App\Http\Controllers\ExerciseDatabaseController;
use App\Http\Controllers\StrengthExercisesController;
use App\Http\Controllers\Api\FoodDiaryController;
use App\Http\Controllers\Api\QuickFoodEntryController;
use App\Http\Controllers\Api\WaterEntryController;
// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

// Registration flow (no auth required until complete)
Route::prefix('register')->group(function () {
    Route::post('/start', [RegistrationController::class, 'start']); // returns flow_id
    Route::post('/{flowId}/step', [RegistrationController::class, 'saveStep']); // body: { step: N, ... }
    Route::post('/{flowId}/complete', [RegistrationController::class, 'complete']); // email/password/username/agree_terms
});

// Authenticated user endpoints
Route::middleware('auth:sanctum')->group(function () {
    // profile routes
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    // goals route
    Route::get('/goals', [GoalController::class, 'index']);
    Route::post('/goals', [GoalController::class, 'store']);
    Route::put('/goals/{goal}', [GoalController::class, 'update']);
    Route::delete('/goals/{goal}', [GoalController::class, 'destroy']);
    // check-ins route
    Route::get('/check-ins', [CheckInController::class, 'index']);
    Route::post('/check-ins', [CheckInController::class, 'store']);
    // exercise database routes
    Route::get('/exercises',[ExerciseDatabaseController::class, 'index']);
    Route::get('/exercises/mine',[ExerciseDatabaseController::class, 'myExercises']);
    Route::get('/exercises/search', [ExerciseDatabaseController::class, 'search']);
    Route::get('/exercises/{id}',[ExerciseDatabaseController::class, 'show']);
    Route::post('/exercises',[ExerciseDatabaseController::class, 'store']);
    Route::put('/exercises/{id}',[ExerciseDatabaseController::class, 'update']);
    Route::delete('/exercises/{id}',[ExerciseDatabaseController::class, 'destroy']);
    // Cardio exercises routes
    Route::get('/cardio-entries', [CardioExercisesController::class, 'index']);
    Route::post('/cardio-entries', [CardioExercisesController::class, 'store']);
    Route::get('/cardio-entries/{id}', [CardioExercisesController::class, 'show']);
    Route::put('/cardio-entries/{id}', [CardioExercisesController::class, 'update']);
    Route::delete('/cardio-entries/{id}', [CardioExercisesController::class, 'destroy']);
    // Strength exercises routes
    Route::get('/strength-entries', [StrengthExercisesController::class, 'index']);
    Route::post('/strength-entries', [StrengthExercisesController::class, 'store']);
    Route::get('/strength-entries/{id}', [StrengthExercisesController::class, 'show']);
    Route::put('/strength-entries/{id}', [StrengthExercisesController::class, 'update']);
    Route::delete('/strength-entries/{id}', [StrengthExercisesController::class, 'destroy']);
    // Quick exercises routes
    Route::get('/quick-entries', [QuickExercisesController::class, 'index']);
    Route::post('/quick-entries', [QuickExercisesController::class, 'store']);
    Route::get('/quick-entries/{id}', [QuickExercisesController::class, 'show']);
    Route::put('/quick-entries/{id}', [QuickExercisesController::class, 'update']);
    Route::delete('/quick-entries/{id}', [QuickExercisesController::class, 'destroy']);
  // Food Diary routes
Route::get('/foods', [FoodDiaryController::class, 'searchFoods']);       // search food items
Route::get('/diary', [FoodDiaryController::class, 'index']);             // get user diary
Route::post('/diary', [FoodDiaryController::class, 'store']);            // add food to diary
Route::delete('/diary/{id}', [FoodDiaryController::class, 'destroy']);   // delete food entry
Route::get('/daily-summary', [FoodDiaryController::class, 'dailySummary']); // nutrition summary(food + quick foods)
// Quick Food Routes
Route::get('/quick-foods', [QuickFoodEntryController::class, 'index']);     // list quick foods
Route::post('/quick-foods', [QuickFoodEntryController::class, 'store']);    // add quick entry
Route::delete('/quick-foods/{id}', [QuickFoodEntryController::class, 'destroy']); // delete quick entry
// Water entry routes
Route::get('/water', [WaterEntryController::class, 'index']);
Route::post('/water', [WaterEntryController::class, 'store']);
Route::delete('/water/{id}', [WaterEntryController::class, 'destroy']);
});

