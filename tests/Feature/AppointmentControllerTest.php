<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_book_an_appointment()
    {
        $doctor = Doctor::factory()->create();
        $schedule = Schedule::create([
            'doctor_id' => $doctor->id,
            'start_time' => '2023-01-01 10:00:00',
            'end_time' => '2023-01-01 11:00:00',
            'is_available' => true,
        ]);

        $response = $this->postJson('/api/v1/book-appointments', [
            'schedule_id' => $schedule->id,
            'patient_id' => 5,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Appointment booked successfully',
                'data' => [
                    'schedule_id' => $schedule->id,
                    'patient_id' => 5,
                ],
            ]);

        $this->assertDatabaseHas('appointments', [
            'schedule_id' => $schedule->id,
            'patient_id' => 5,
        ]);

        $this->assertDatabaseHas('schedules', [
            'id' => $schedule->id,
            'is_available' => false,
        ]);
    }

    public function test__returns_error_when_schedule_not_found()
    {

        $response = $this->postJson('/api/v1/book-appointments', [
            'schedule_id' => 999,
            'patient_id' => 5,
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'errors' => [
                    [
                        'code' => 'schedule_not_found',
                        'message' => 'Schedule not found',
                    ],
                ],
                'data' => null,
            ]);
    }

    public function test_returns_error_when_slot_already_booked()
    {
        $doctor = Doctor::factory()->create();
        $schedule = Schedule::create([
            'doctor_id' => $doctor->id,
            'start_time' => '2023-01-01 10:00:00',
            'end_time' => '2023-01-01 11:00:00',
            'is_available' => false,
        ]);

        $response = $this->postJson('/api/v1/book-appointments', [
            'schedule_id' => $schedule->id,
            'patient_id' => 5,
        ]);

        $response->assertStatus(409)
            ->assertJson([
                'errors' => [
                    [
                        'code' => 'slot_already_booked',
                        'message' => 'This time slot is already booked',
                    ],
                ],
                'data' => null,
            ]);
    }

    public function test_validates_required_fields()
    {
        $response = $this->postJson('/api/v1/book-appointments', []);

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'code' => 'validation_failed',
                        'message' => 'Validation failed',
                        'meta' => [
                            'schedule_id' => [
                                'The schedule id field is required.',
                            ],
                            'patient_id' => [
                                'The patient id field is required.',
                            ],
                        ],
                    ],
                ],
                'data' => null,
            ]);
    }
}
