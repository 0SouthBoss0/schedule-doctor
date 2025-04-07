<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AppointmentController;

Route::post('/POST/api/v1/add-slots', [ScheduleController::class, 'addSlot']);
Route::get('/GET/api/v1/free-slots/{doctorId}', [ScheduleController::class, 'getFreeSlots']);
Route::post('/POST/api/v1/book-appointments', [AppointmentController::class, 'bookAppointment']);
