<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_a_time_slot_for_a_doctor()
    {
        $doctor = Doctor::factory()->create();

        $response = $this->postJson('/api/v1/add-slots', [
            'doctor_id' => $doctor->id,
            'start_time' => '2023-01-01 10:00:00',
            'end_time' => '2023-01-01 11:00:00',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'errors' => [],
                'data' => [
                    'doctor_id' => $doctor->id,
                    'start_time' => '2023-01-01 10:00:00',
                    'end_time' => '2023-01-01 11:00:00',
                    'is_available' => true,
                ],
            ]);

        $this->assertDatabaseHas('schedules', [
            'doctor_id' => $doctor->id,
            'start_time' => '2023-01-01 10:00:00',
            'end_time' => '2023-01-01 11:00:00',
        ]);
    }

    public function test_returns_error_when_doctor_not_found()
    {
        $response = $this->postJson('/api/v1/add-slots', [
            'doctor_id' => 999,
            'start_time' => '2023-01-01 10:00:00',
            'end_time' => '2023-01-01 11:00:00',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'errors' => [
                    [
                        'code' => 'doctor_not_found',
                        'message' => 'Doctor not found',
                    ],
                ],
                'data' => null,
            ]);
    }

    public function test_returns_error_when_time_slot_conflicts()
    {
        $doctor = Doctor::factory()->create();
        Schedule::create([
            'doctor_id' => $doctor->id,
            'start_time' => '2023-01-01 10:00:00',
            'end_time' => '2023-01-01 11:00:00',
            'is_available' => true,
        ]);

        $response = $this->postJson('/api/v1/add-slots', [
            'doctor_id' => $doctor->id,
            'start_time' => '2023-01-01 10:30:00',
            'end_time' => '2023-01-01 11:30:00',
        ]);

        $response->assertStatus(409)
            ->assertJson([
                'errors' => [
                    [
                        'code' => 'time_conflict',
                        'message' => 'Time slot conflicts with existing schedule',
                    ],
                ],
                'data' => null,
            ]);
    }

    public function test_can_get_free_slots_for_a_doctor()
    {
        $doctor = Doctor::factory()->create();
        $schedule = Schedule::create([
            'doctor_id' => $doctor->id,
            'start_time' => '2023-01-01 10:00:00',
            'end_time' => '2023-01-01 11:00:00',
            'is_available' => true,
        ]);

        $response = $this->getJson("/api/v1/free-slots/{$doctor->id}");

        $response->assertStatus(200)
            ->assertJson([
                'errors' => [],
                'data' => [
                    [
                        'id' => $schedule->id,
                        'doctor_id' => $doctor->id,
                        'start_time' => '2023-01-01 10:00:00',
                        'end_time' => '2023-01-01 11:00:00',
                        'is_available' => true,
                    ],
                ],
            ]);
    }

    public function test_returns_empty_when_no_free_slots()
    {
        $doctor = Doctor::factory()->create();

        $response = $this->getJson("/api/v1/free-slots/{$doctor->id}");

        $response->assertStatus(201)
            ->assertJson([
                'errors' => [],
                'data' => null,
            ]);
    }
}
