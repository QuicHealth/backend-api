<?php

namespace App\Actions;

use App\Events\NotificationReceived;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Schedule;
use App\Models\User;
use App\Notifications\CreateAppointmentNotification;
use Illuminate\Http\Response;
use Lorisleiva\Actions\Concerns\AsAction;


class CreateAppointmentAction
{
    use AsAction;

    public $validated;

    public $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function handle($validated, $user_id)
    {
        $this->validated = $validated;

        $checkSchedules = $this->checkSchedules();

        if ($checkSchedules) {

            $checkBooking = $this->checkAppointmentBooking();

            if ($checkBooking['status'] !== 'success') {
                return  response([
                    'status' => false,
                    'message' => $checkBooking['message'],
                ], 404);

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
        }


        // return $appointment;
    }

    public function checkAppointmentBooking()
    {
        $checkAppointmentBooking = Appointment::where('doctor_id', $this->validated['doctor_id'])
            ->where('date', $this->validated['date'])
            ->where('start', $this->validated['time_slots']['start'])
            ->where('end', $this->validated['time_slots']['end'])
            ->first();

        if ($checkAppointmentBooking != null) {
            // if (in_array($checkAppointmentBooking->payment_status, array('pending', 'PAID'))) {
            // return $checkAppointmentBooking;
            // return
            // }

            if ($checkAppointmentBooking->payment_status == 'pending') {
                return [
                    'status' => 'warning',
                    'message' => 'Time slot have already been selected but have not been paid, select another time slot',
                ];
            }

            if ($checkAppointmentBooking->payment_status == 'PAID') {
                return [
                    'status' => "error",
                    'message' => 'Time slot have already been selected, select another time slot',
                ];
            }
        }

        return [
            'status' => 'success',
            'message' => 'Time slot available',
        ];
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

    // check if Schedules exists
    public function checkSchedules()
    {
        $checkschedules = $this->schedule->where('doctor_id', $this->validated['doctor_id'])
            ->where('date', $this->validated['date'])
            ->whereHas('timeslot', function ($query) {
                $query->where('start', $this->validated['time_slots']['start']);
                $query->where('end', $this->validated['time_slots']['end']);
            })
            ->first();

        if ($checkschedules) {
            return $checkschedules;
        } else {
            // abort(Response::HTTP_NOT_FOUND, "Schedules not available!");
            return [
                'status' => false,
                'message' => 'Schedules not available!',
            ];
        }
    }
}