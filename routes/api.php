<?php

use App\Http\Controllers\API\ConstructionController;
use App\Http\Controllers\API\FurnitureController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\RoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    // Get Request User's Info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Construction / Room / Furniture Resource
    Route::apiResources(
        resources:[
            'construction' => ConstructionController::class,
            'rooms'        => RoomController::class,
            'furniture'    => FurnitureController::class
        ],
        options: ['missing' => fn () => response()->json('Error 404: No Resource Found!', 404)]
    );

    // Item Resource
    Route::apiResource(
        name: 'items',
        controller: ItemController::class,
        options: ['only' => ['index', 'show', 'destroy']],
    );
});
