<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JokeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/joke', [JokeController::class, 'index'])->name('joke.index');
Route::post('/joke/fetch', [JokeController::class, 'fetchJoke'])->name('joke.fetch');
