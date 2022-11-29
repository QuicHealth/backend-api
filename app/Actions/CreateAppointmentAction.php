<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Doctor;
use App\Classes\Helpers;
use App\Models\Schedule;
use App\Models\Appointment;
use App\Models\Notification;
use Illuminate\Http\Response;
use App\Events\NotificationReceived;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Notifications\CreateAppointmentNotification;


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


            $checkDoctorSchedules = $this->checkSchedules();

            if ($checkDoctorSchedules['status'] !== 'success') {
                return response([
                    'status' => 'error',
                    'message' => $checkDoctorSchedules['message'],
                ], 404);
            }


            $checkBooking = $this->checkAppointmentBooking();

            if ($checkBooking['status'] !== 'success') {
                return  response([
                    'status' => false,
                    'message' => $checkBooking['message'],
                ], 404);
            }



            $appointment =  $this->createAppointment($user_id);

            if ($appointment) {

                return response([
                    'status' => true,
                    'message' => 'Success! Appointment created',
                    'Appointments' => $appointment,
                ], 202);
            } else {

                return response([
                    'status' => false,
                    'message' => 'Error rescheduling, pls try again',
                ], 406);
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

        $patientFullname = $user->firstname . ' ' . $user->lastname;

        $doctorName = Helpers::getField(new Doctor, $appointment->doctor_id, 'name');
        $patientMessage = "You have created a new appointment with Doctor, $doctorName on $appointment->date at $appointment->start, please make your payment to confirm your appointment booking .";
        $doctorMessage = "You have a new appointment with $patientFullname on $appointment->date at $appointment->start, we will let you know when the patient makes payment.";

        Helpers::saveNotification($user_id, 'patient', 'appointment', $patientMessage, 'Appointment Booking');
        Helpers::saveNotification($appointment->doctor_id, 'doctor', 'appointment', $doctorMessage, 'Appointment Booking');

        //save db notification
        // $notification = new Notification();
        // $notification->user_id = auth()->user()->id;
        // $notification->receiverId = $appointment->doctor_id;
        // $notification->categories = 'Appointment';
        // $notification->user_type = 'Patient';
        // $notification->title = 'Appointment Created';
        // $notification->message = $patientMessage;
        // $notification->save();

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
            })->first();


        if ($checkschedules) {
            return [
                'status' => 'success',
                'message' => 'Time slot available',
            ];
        } else {
            // abort(Response::HTTP_NOT_FOUND, "Schedules not available!");
            return [
                'status' => 'error',
                'message' => 'Time slot not available for this doctor!',
            ];
        }
    }
}