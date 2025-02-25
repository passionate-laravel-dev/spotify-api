<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\SpotifyController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('signin', [AuthenticationController::class, 'signin']);
    Route::post('signup', [AuthenticationController::class, 'signup']);
})->withoutMiddleware('auth:sanctum');

Route::prefix('spotify')->middleware('auth:sanctum')->group(function () {
    Route::get('search-items', [SpotifyController::class, 'searchItems']);

    Route::prefix('artists')->group(function () {
        Route::get('/', [SpotifyController::class, 'getArtist']);
        Route::get('/several', [SpotifyController::class, 'getSeveralArtists']);
        Route::get('/{id}/albums', [SpotifyController::class, 'getArtistAlbums']);
    });
});
