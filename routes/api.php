<?php

use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;

Route::withoutMiddleware(['api'])->group(function () {
	Route::post('/tickets', [TicketController::class, 'store']);#->middleware('web');
	Route::get('/tickets/statistics', [TicketController::class, 'statistics']);
});
