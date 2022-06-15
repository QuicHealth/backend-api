<?php

namespace App\Actions;

use App\Models\Appointment;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Requests\AppointmentDetailsRequest;
use Illuminate\Http\Request;



class CreateAppointmentAction
{
    use AsAction;

    public $validated;

    public AppointmentDetailsRequest $request;


    public function handle($validated, $user_id, $date)
    {
        $this->validated = $validated;

        $checkBooking = $this->checkAppointmentBooking();

        if ($checkBooking) {

            $data = [
                response([
                    'status' => false,
                    'message' => 'Time slot have already been booked',
                ], 403)
            ];

            return $data;
        }

        $appointment =  $this->createAppointment($user_id, $date);

        // AppointmentDetailsAction::run($this->request, $appointment->id);

        if ($appointment) {
            UpdateTimeslotStatus::run($this->validated['doctor_unique_id'], $this->validated['day_id'], $this->validated['time_slots']);
            return response([
                'status' => true,
                'message' => 'Success! Appointment created',
                'Appointments' => $appointment,
            ], http_response_code());
        } else {

            return response([
                'status' => false,
                'message' => 'Error rescheduling, pls try again',
            ], http_response_code());
        }

        return $appointment;
    }

    public function checkAppointmentBooking()
    {
        $appointment = new Appointment();

        $checkAppointmentBooking = $appointment->where('doctor_unique_id', $this->validated['doctor_unique_id'])
            ->where('day_id', $this->validated['day_id'])
            ->where('start', $this->validated['time_slots']['start'])
            ->where('end', $this->validated['time_slots']['end'])
            ->first();

        return $checkAppointmentBooking;
    }

    public function createAppointment($user_id, $date)
    {
        $appointment = Appointment::create([
            "user_id" => $user_id,
            "doctor_unique_id" => $this->validated['doctor_unique_id'],
            "day_id" => $this->validated['day_id'],
            "start" => $this->validated['time_slots']['start'],
            "end" => $this->validated['time_slots']['end'],
            "date" => $date,
            "status" => 'pending',
            "payment_status" => 'pending',
            "payment_reference" => '',
            "unique_id" => uniqid(),
        ]);

        return $appointment;
    }
}