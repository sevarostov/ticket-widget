<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
	Route::get('/ticket', [TicketController::class, 'index'])->name('ticket.index');
	Route::get('/ticket/{id}', [TicketController::class, 'show'])->name('ticket.show');
	Route::post('/ticket/{id}/status', [TicketController::class, 'updateStatus'])->name('ticket.updateStatus');
});
