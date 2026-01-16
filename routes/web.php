<?php

use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('expenses.index')
        : redirect()->route('login');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('expenses', ExpenseController::class)->middleware('auth');

