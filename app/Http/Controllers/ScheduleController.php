<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;


class ScheduleController extends Controller
{

    public function addSlot(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $schedule = Schedule::create([
            'doctor_id' => $request->doctor_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return response()->json($schedule, 201);
    }


    public function getFreeSlots($doctorId)
    {
        $freeSlots = Schedule::where('doctor_id', $doctorId)
            ->where('is_available', true)
            ->get();

        return response()->json($freeSlots);
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
