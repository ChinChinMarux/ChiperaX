<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CipherController;

Route::get('/', [CipherController::class, 'index'])->name('cipher.index');
Route::post('/process', [CipherController::class, 'process'])->name('cipher.process');
