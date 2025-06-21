<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/api/books', [BookController::class, 'index']);
Route::post('/api/books', [BookController::class, 'store']);
Route::get('/api/books/{id}', [BookController::class, 'show']);
Route::put('/api/books/{id}', [BookController::class, 'update']);
Route::delete('/api/books/{id}', [BookController::class, 'destroy']);