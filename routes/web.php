<?php

use App\Http\Controllers\KrogerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/kroger/login', [KrogerController::class, 'redirectToKroger']);
Route::get('/kroger/callback', [KrogerController::class, 'handleKrogerCallback']);