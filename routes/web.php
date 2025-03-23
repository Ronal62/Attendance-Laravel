<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/absensi', function () {
    return view('pages.absensi');
})->name('absensi');

Route::get('/tambah-user', function () {
    return view('pages.tambah-user');
})->name('tambah-user');