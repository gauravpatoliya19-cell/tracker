<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackController;

// મેઈન પેજ
Route::get('/', function () {
    return "App Working";
});

// ટ્રેકિંગ માટેનો રૂટ (ગૂગલ પર રીડાયરેક્ટ કરશે)
Route::get('/google', [TrackController::class, 'track'])->name('track');

// ડેશબોર્ડ રૂટ (ડેટા જોવા અને સર્ચ કરવા માટે)
Route::get('/dashboard', [TrackController::class, 'dashboard'])->name('dashboard');

// સિંગલ રેકોર્ડ ડિલીટ કરવા માટે
Route::delete('/click/delete/{id}', [TrackController::class, 'destroy'])->name('click.delete');

// બધો જ ડેટા એકસાથે ડિલીટ કરવા માટે
// ખાતરી કરજો કે Blade માં {{ route('clicks.deleteall') }} જ લખ્યું હોય
Route::delete('/clicks/delete-all', [TrackController::class, 'destroyAll'])->name('clicks.deleteall');
