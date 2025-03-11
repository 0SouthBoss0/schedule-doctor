<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Schedule;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{

    public function bookAppointment(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'patient_id' => 'required|integer'

        ]);

        $schedule = Schedule::find($request->schedule_id);

        if (!$schedule->is_available) {
            return response()->json(['message' => 'This slot is already booked'], 400);
        }

        $appointment = Appointment::create([
            'schedule_id' => $request->schedule_id,
            'patient_id' => $request->patient_id
        ]);

        $schedule->update(['is_available' => false]);

        return response()->json($appointment, 201);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
