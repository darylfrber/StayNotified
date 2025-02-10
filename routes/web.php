<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//    ]);
//});

Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{event}', [EventController::class, 'update']);
    Route::delete('/events/{event}', [EventController::class, 'destroy']);


    // Notes
    Route::get('/notes', [NotesController::class, 'index'])->name('notes.index');
    Route::get('/notes/{note}', [NotesController::class, 'show'])->name('notes.show');
    Route::post('/notes', [NotesController::class, 'store'])->name('notes.store');
    Route::post('/notes/{note}/increment-views', [NotesController::class, 'incrementViews']);
    Route::put('/notes/{note}', [NotesController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [NotesController::class, 'destroy'])->name('notes.destroy');

});


require __DIR__.'/auth.php';
