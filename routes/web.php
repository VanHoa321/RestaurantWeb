<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Account\AccountController;

Route::get('/',[AccountController::class, "login"])->name("index");

//Account
Route::get('/dang-nhap', [AccountController::class, 'login'])->name('login');
Route::post('/login', [AccountController::class, 'postLogin'])->name('postLogin');