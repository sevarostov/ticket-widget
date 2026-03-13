<?php

use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;

Route::post('/tickets', [TicketController::class, 'store'])->withoutMiddleware('api')->middleware('web');
Route::get('/tickets/statistics', [TicketController::class, 'statistics']);
