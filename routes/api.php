<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AppointmentController;

Route::post('/add-slot', [ScheduleController::class, 'addSlot']);
Route::get('/free-slots/{doctorId}', [ScheduleController::class, 'getFreeSlots']);
Route::post('/book-appointment', [AppointmentController::class, 'bookAppointment']);
