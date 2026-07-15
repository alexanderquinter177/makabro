<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\SelectSede;

Route::redirect('/gestion', '/');
Route::redirect('/gestion/login', '/login');

Route::get('/select-sede', SelectSede::class)
    ->name('select-sede')
    ->middleware('auth');
