<?php

namespace App\Actions;

use App\Events\NotificationReceived;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\CreateAppointmentNotification;
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

            return  response([
                'status' => false,
                'message' => 'Time slot have already been booked or selected',
            ], 403);

            // return $data;
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


        // return $appointment;
    }

    public function checkAppointmentBooking()
    {

        $checkAppointmentBooking = Appointment::where('doctor_id', $this->validated['doctor_id'])
            ->where('date', $this->validated['date'])
            ->where('start', $this->validated['time_slots']['start'])
            ->where('end', $this->validated['time_slots']['end'])
            ->orWhere('payment_status', 'PAID')
            ->orWhere('payment_status', 'pending')
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

        $user = User::find($user_id);
        $appointment->user()->associate($user);

        // $appointment->notify(new CreateAppointmentNotification($appointment));

        //save db notification
        $notification = new Notification();
        $notification->user_id = auth()->user()->id;
        $notification->receiverId = $appointment->doctor_id;
        $notification->user_type = 'Patient';
        $notification->title = 'Appointment Created';
        $notification->message = 'Appointment was Created Successful';
        $notification->save();

        return $appointment;
    }
}