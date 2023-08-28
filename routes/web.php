<?php

use Illuminate\Support\Facades\Route;
use App\Models\Movie;
use App\Http\Controllers\MovieController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/search-movies/{title}',[MovieController::class,'searchMovies']);
    Route::get('/check-duplicates/{imdbID}',[MovieController::class,'checkDuplicates']);
    Route::post('/favorites',[MovieController::class,'store']);
    Route::get('/mis-favoritas', function () {
        return view('my-favorites');
    })->name('mis-favoritas');
    Route::get('/favorites',[MovieController::class,'index']);
    Route::delete('favorites/{id}',[MovieController::class,'destroy']);
});
