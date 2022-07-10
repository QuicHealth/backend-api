<?php

namespace App\Actions;

use App\Events\NotificationReceived;
use App\Models\Appointment;
use Lorisleiva\Actions\Concerns\AsAction;


class CreateAppointmentAction
{
    use AsAction;

    public $validated;


    public function handle($validated, $user_id)
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

        $appointment =  $this->createAppointment($user_id);

        if ($appointment) {

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

        $checkAppointmentBooking = Appointment::where('doctor_id', $this->validated['doctor_id'])
            ->where('date', $this->validated['date'])
            ->where('start', $this->validated['time_slots']['start'])
            ->where('end', $this->validated['time_slots']['end'])
            ->where('payment_status', 'PAID')
            ->first();

        return $checkAppointmentBooking;
    }

    public function createAppointment($user_id)
    {
        $appointment = Appointment::create([
            "user_id" => $user_id,
            "doctor_id" => $this->validated['doctor_id'],
            "date" => $this->validated['date'],
            "start" => $this->validated['time_slots']['start'],
            "end" => $this->validated['time_slots']['end'],
            "status" => 'pending',
            "payment_status" => 'pending',
            "unique_id" => uniqid(),
        ]);

        return $appointment;

        // if ($appointment) {
        //     event(new NotificationReceived($user_id));
        //     return $appointment;
        // }
    }
}