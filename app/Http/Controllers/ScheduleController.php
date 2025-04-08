<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Schedule;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    private const ERROR_CODES = [
        'VALIDATION_FAILED' => 'validation_failed',
        'DOCTOR_NOT_FOUND' => 'doctor_not_found',
        'TIME_CONFLICT' => 'time_conflict',
        'NO_SLOTS_FOUND' => 'no_slots_found',
        'INTERNAL_ERROR' => 'internal_error',
    ];

    public function addSlot(Request $request)
    {
        try {
            $validated = $request->validate([
                'doctor_id' => 'required|integer|min:1',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
            ]);

            $doctor = Doctor::find($validated['doctor_id']);

            if (! $doctor) {
                return response()->json([
                    'errors' => [[
                        'code' => self::ERROR_CODES['DOCTOR_NOT_FOUND'],
                        'message' => 'Doctor not found',
                    ]],
                    'data' => null,
                ], 404);
            }

            $conflictingSlot = Schedule::where('doctor_id', $validated['doctor_id'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('start_time', '<=', $validated['start_time'])
                                ->where('end_time', '>=', $validated['end_time']);
                        });
                })
                ->exists();

            if ($conflictingSlot) {
                return response()->json([
                    'errors' => [[
                        'code' => self::ERROR_CODES['TIME_CONFLICT'],
                        'message' => 'Time slot conflicts with existing schedule',
                    ]],
                    'data' => null,
                ], 409);
            }

            $schedule = Schedule::create([
                'doctor_id' => $validated['doctor_id'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'is_available' => true,
            ]);

            return response()->json([
                'errors' => [],
                'data' => $schedule,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'errors' => [[
                    'code' => self::ERROR_CODES['VALIDATION_FAILED'],
                    'message' => 'Validation failed',
                    'meta' => $e->errors(),
                ]],
                'data' => null,
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'errors' => [[
                    'code' => self::ERROR_CODES['INTERNAL_ERROR'],
                    'message' => 'Failed to add time slot',
                    'meta' => ['details' => $e->getMessage()],
                ]],
                'data' => null,
            ], 500);
        }
    }

    public function getFreeSlots($doctorId)
    {
        try {
            if (! Doctor::find($doctorId)) {
                return response()->json([
                    'errors' => [[
                        'code' => self::ERROR_CODES['DOCTOR_NOT_FOUND'],
                        'message' => 'Doctor not found',
                    ]],
                    'data' => null,
                ], 404);
            }

            $freeSlots = Schedule::where('doctor_id', $doctorId)
                ->where('is_available', true)
                ->get();

            if ($freeSlots->isEmpty()) {
                return response()->json([
                    'errors' => [],
                    'data' => null,
                ], 201);
            } else {
                return response()->json([
                    'errors' => [],
                    'data' => $freeSlots->toArray(),
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'errors' => [[
                    'code' => self::ERROR_CODES['INTERNAL_ERROR'],
                    'message' => 'Failed to retrieve free slots',
                    'meta' => ['details' => $e->getMessage()],
                ]],
                'data' => null,
            ], 500);
        }
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
