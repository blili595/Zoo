<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\EnclosureController;
use App\Models\Animal;
use App\Models\Enclosure;
use App\Helpers\Helper;
use App\Models\User;
use App\Http\Middleware\Admin;


Route::get('/', function () {
    $user = Auth::user();
    $enclosureCount = Enclosure::count();
    $animals = Animal::count();
    if ($user->admin) {
        $enclosured = Enclosure::all();
    } else {
        $enclosured = $user->enclosures()->with('animals')->get();
    }
    return view('home', compact('enclosureCount', 'animals', 'enclosured'));

})->middleware(['auth', 'verified'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::patch('/animals/{id}/restore', [AnimalController::class, 'restore'])->name('animals.restore');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('enclusures', EnclosureController::class);

});

Route::get('/animals/archived', [AnimalController::class, 'archived'])->name('animals.archived');


Route::middleware(['auth', 'verified', Admin::class])->group(function () {
    Route::resource('animals', AnimalController::class)->only([
        'index',  'create', 'store', 'edit', 'update', 'destroy', 'archived'
    ]);
});
Route::middleware(['auth'])->group(function () {
    Route::resource('animals', AnimalController::class)->only([
        'show'
    ]);
});
Route::post('/animals', [AnimalController::class, 'store'])->name('animals.store');

Route::middleware(['auth', 'verified', Admin::class])->group(function () {
    Route::resource('enclosures', EnclosureController::class)->only([
         'create', 'store', 'edit', 'update', 'destroy'
    ]);
});
Route::middleware(['auth'])->group(function () {
    Route::resource('enclosures', EnclosureController::class)->only([
        'show', 'index'
    ]);
});


Route::post('/animals/{id}/restore', [AnimalController::class, 'restore'])->name('animals.restore');


require __DIR__.'/auth.php';
