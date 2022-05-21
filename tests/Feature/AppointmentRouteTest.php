<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Appointment;
use App\Models\User;
use Tests\TestCase;

class AppointmentRouteTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC92MVwvbG9naW4iLCJpYXQiOjE2NDAwMzMxNDQsImV4cCI6MTY0MDAzNjc0NCwibmJmIjoxNjQwMDMzMTQ0LCJqdGkiOiJkMms4MkIyQ3lpMzhSbHFpIiwic3ViIjoxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.v35pJi_Nuwj5lqoLyin9oHHbdfq5oJ_6lcq_ijzxHTk';
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_getAll_appointment_route()
    {
        $user = factory(User::class)->create();
        $appointment = factory('App\Appointment')->create();
        $this->actingAs($user, 'api');

        $this->json('GET', '/api/v1/appointments', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "status" => true,
            ]);
    }

    public function test_get_an_apppointment_route()
    {
        $user = factory(User::class)->create();
        $appointment = factory('App\Appointment')->create();
        $this->actingAs($user, 'api');

        $this->json('GET', '/api/v1/appointment/1', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "status" => true,
            ]);
    }

    public function test_reschedule_appointment_route()
    {
        $user = factory(User::class)->create();
        $appointment = factory('App\Appointment')->create();
        $this->actingAs($user, 'api');
        $data = [
            'user_id' => 1,
            'doctor_id' => 1,
            'day_id' => 1,
            'from' => "3pm",
            'to' => "4pm",
            'status' => 'pending',
        ];

        $this->json('PUT', '/api/v1/reschedule-appointment/1', $data, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "status" => true,
            ]);
    }

    public function test_cancel_appointment_route()
    {
        $user = factory(User::class)->create();
        $appointment = factory('App\Appointment')->create();
        $this->actingAs($user, 'api');

        $data = [
            'user_id' => 1,
            'doctor_id' => 1,
            'day_id' => 1,
            'from' => "3pm",
            'to' => "4pm",
            'status' => 'cancel',
        ];

        $this->json('POST', '/api/v1/cancel-appointment/1', $data, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "status" => true,
            ]);
    }

    public function test_appointment_report_route()
    {
        $response = $this->get('/api/v1/appointment-report/{id}');

        $response->assertStatus(200);
    }
}