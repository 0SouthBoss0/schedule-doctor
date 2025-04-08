<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/add-slots', [ScheduleController::class, 'addSlot']);
Route::get('/v1/free-slots/{doctorId}', [ScheduleController::class, 'getFreeSlots']);
Route::post('/v1/book-appointments', [AppointmentController::class, 'bookAppointment']);
