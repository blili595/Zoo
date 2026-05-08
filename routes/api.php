<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiController;
use App\Http\Middleware\ValidateParams;
use App\Models\Enclosure;


//authentikacios

Route::post('/register', [ApiController::class, 'register'])->name('api.register');

Route::post('/login', [ApiController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiController::class, 'logout'])->name('api.logout');
    Route::get('/user',[ApiController::class, 'user'])->name('api.user');
});

//enclosures muveletek

Route::get('enclosures/{id?}', [ApiController::class, 'getEnclosures'] )->name('api.enclosures.get')->where('id', '[0-9]+');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('enclosures/', [ApiController::class, 'store'] )->name('api.enclosures.store');
    Route::put('enclosures/{id}', [ApiController::class, 'update'] )->name('api.enclosures.update');
    Route::delete('enclosures/{id}', [ApiController::class, 'destroy'] )->name('api.enclosures.destroy');
    
});

// request URI parameterek validalasa

Route::get('uri-params1/{number}/{string}/{optional?}', function($number, $string, $optional = null) {
    return response()->json([
        'number' => $number,
        'string' => $string,
        'optional' => $optional
        
    ]);
})->where('number', '[0-9]+')->where('string', '[a-zA-Z0-9]+');


Route::get('uri-params2/{number}/{string}/{optional?}', function($number, $string, $optional = null) {
    return response()->json([
        'number' => $number,
        'string' => $string,
        'optional' => $optional
        
    ]);
})->middleware(ValidateParams::class);



