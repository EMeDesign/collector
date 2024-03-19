<?php

use App\Livewire\Rooms\SearchRoom;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome')->name('welcome');

Route::view('profile', 'profile')->middleware(['auth'])->name('profile');


/*
|--------------------------------------------------------------------------
| Authed And Verified Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web, auth and verified" middleware group. Make something great!
|
*/

Route::middleware(['auth', 'verified'])->group(function () {
    /* Dashboard */
    Route::view('dashboard', 'dashboard')->name('dashboard');

    /* Constructions */
    Route::prefix('constructions')->name('constructions.')->group(function () {
        Volt::route('/', 'constructions.search-construction')->name('search-construction');
        Volt::route('/create', 'constructions.create-construction')->name('create-construction');
        Volt::route('/{construction}/edit', 'constructions.edit-construction')->name('edit-construction');
    });

    /* Rooms */
    Route::prefix('rooms')->name('rooms.')->group(function () {
        Volt::route('/', 'rooms.search-room')->name('search-room');
        Volt::route('/create', 'rooms.create-room')->name('create-room');
        Volt::route('/{room}/edit', 'rooms.edit-room')->name('edit-room');
    });

    /* Furniture */
    Route::prefix('furniture')->name('furniture.')->group(function () {
        Volt::route('/', 'furniture.search-furniture')->name('search-furniture');
        Volt::route('/create', 'furniture.create-furniture')->name('create-furniture');
        Volt::route('/{furniture}/edit', 'furniture.edit-furniture')->name('edit-furniture');
    });

    /* Items */
    Route::prefix('items')->name('items.')->group(function () {
        Volt::route('/', 'items.search-item')->name('search-item');
        Volt::route('/create', 'items.create-item')->name('create-item');
        Volt::route('/{item}/edit', 'items.edit-item')->name('edit-item');
    });
});

require __DIR__.'/auth.php';
