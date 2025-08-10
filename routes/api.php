<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RubricController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AdminAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Routes d'authentification pour les admins
Route::prefix('auth')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [AdminAuthController::class, 'me'])->middleware('auth:sanctum');
});

// Routes API publiques (lecture seule)
Route::prefix('v1')->group(function () {
    // Rubrics publiques
    Route::get('/rubrics', [RubricController::class, 'index']);
    Route::get('/rubrics/{id}', [RubricController::class, 'show']);
    
    // Articles publiques
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{id}', [ArticleController::class, 'show']);
    Route::get('/rubrics/{rubricId}/articles', [ArticleController::class, 'getByRubric']);
    
    // Media publiques
    Route::get('/media', [MediaController::class, 'index']);
    Route::get('/media/{id}', [MediaController::class, 'show']);
});

// Routes API protégées (authentification requise)
Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    // Gestion des rubrics (admin)
    Route::post('/rubrics', [RubricController::class, 'store']);
    Route::put('/rubrics/{id}', [RubricController::class, 'update']);
    Route::delete('/rubrics/{id}', [RubricController::class, 'destroy']);
    
    // Gestion des articles (admin)
    Route::post('/articles', [ArticleController::class, 'store']);
    Route::put('/articles/{id}', [ArticleController::class, 'update']);
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);
    
    // Gestion des media (admin)
    Route::post('/media', [MediaController::class, 'store']);
    Route::put('/media/{id}', [MediaController::class, 'update']);
    Route::delete('/media/{id}', [MediaController::class, 'destroy']);
    
    // Gestion des admins (super_admin uniquement)
    Route::middleware(['check.super.admin'])->group(function () {
        Route::get('/admins', [AdminController::class, 'index']);
        Route::get('/admins/{id}', [AdminController::class, 'show']);
        Route::post('/admins', [AdminController::class, 'store']);
        Route::put('/admins/{id}', [AdminController::class, 'update']);
        Route::delete('/admins/{id}', [AdminController::class, 'destroy']);
    });
});
