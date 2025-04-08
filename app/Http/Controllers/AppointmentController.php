<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Schedule;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    private const ERROR_CODES = [
        'VALIDATION_FAILED' => 'validation_failed',
        'SCHEDULE_NOT_FOUND' => 'schedule_not_found',
        'SLOT_ALREADY_BOOKED' => 'slot_already_booked',
        'PATIENT_NOT_FOUND' => 'patient_not_found',
        'INTERNAL_ERROR' => 'internal_error',
    ];

    public function bookAppointment(Request $request)
    {

        try {
            $validated = $request->validate([
                'schedule_id' => 'required|integer|min:1',
                'patient_id' => 'required|integer|min:1',
            ]);

            $schedule = Schedule::find($validated['schedule_id']);

            if (! $schedule) {
                return response()->json([
                    'errors' => [
                        [
                            'code' => self::ERROR_CODES['SCHEDULE_NOT_FOUND'],
                            'message' => 'Schedule not found',
                        ],
                    ],
                    'data' => null,
                ], 404);
            }

            if (! $schedule->is_available) {
                return response()->json([
                    'errors' => [
                        [
                            'code' => self::ERROR_CODES['SLOT_ALREADY_BOOKED'],
                            'message' => 'This time slot is already booked',
                        ],
                    ],
                    'data' => null,
                ], 409);
            }

            $appointment = Appointment::create([
                'schedule_id' => $validated['schedule_id'],
                'patient_id' => $validated['patient_id'],
            ]);

            $schedule->update(['is_available' => false]);

            return response()->json([
                'message' => 'Appointment booked successfully',
                'data' => $appointment,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'errors' => [
                    [
                        'code' => self::ERROR_CODES['VALIDATION_FAILED'],
                        'message' => 'Validation failed',
                        'meta' => $e->errors(),
                    ],
                ],
                'data' => null,
            ], 422);
        } catch (Exception $e) {

            return response()->json([
                'errors' => [
                    [
                        'code' => self::ERROR_CODES['INTERNAL_ERROR'],
                        'message' => 'Failed to book appointment',
                        'meta' => ['details' => $e->getMessage()],
                    ],
                ],
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
