<?php

use App\Http\Controllers\Api\SpotifyController;
use Illuminate\Support\Facades\Route;

Route::prefix('spotify')->group(function () {
    Route::get('search-items', [SpotifyController::class, 'searchItems']);

    Route::prefix('artists')->group(function () {
        Route::get('/', [SpotifyController::class, 'getArtist']);
        Route::get('/several', [SpotifyController::class, 'getSeveralArtists']);
        Route::get('/{id}/albums', [SpotifyController::class, 'getArtistAlbums']);
    });
});
