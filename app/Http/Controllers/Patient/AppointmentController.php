<?php

namespace App\Http\Controllers\Patient;

use App\Models\User;
use App\Models\Zoom;
use App\Models\Doctor;
use App\Models\Report;
use App\Models\Appointment;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Events\NotificationReceived;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\UpdateTimeslotStatus;
use App\Actions\CreateAppointmentAction;
use App\Actions\AppointmentDetailsAction;
use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\AppointmentDetailsRequest;
use App\Notifications\CancelAppointmentNotification;

class AppointmentController extends Controller
{
    public function createAppointment(CreateAppointmentRequest $request)
    {
        $validated = $request->validated();

        $user_id = Auth::user($request->token)->id;

        return CreateAppointmentAction::run($validated, $user_id);
    }

    public function appointmentDetails(AppointmentDetailsRequest $request)
    {
        $validated = $request->validated();

        $data = [
            'appointment_id' =>  $validated['appointment_id'],
            'purpose' =>  $validated['purpose'],
            'symptoms' =>  $validated['symptoms'],
            'allergies' =>  $validated['allergies'],
            'medications' =>  $validated['medications'],
            'others' =>  $validated['others'],
        ];



        $details = AppointmentDetailsAction::run($data);

        if ($details) {
            return response([
                'status' => true,
                'message' => 'Success! Proceed to payment',
                'Appointments' => $details,
            ], http_response_code());
        } else {
            return response([
                'status' => false,
                'message' => 'Sorry! this Appointment has not been created',
            ], http_response_code());
        }
    }

    public function getAll(Request $request)
    {
        $appointments = Appointment::where('user_id', Auth::user($request->token)->id)
            ->with('doctor')
            ->get();

        if ($appointments) {
            return response([
                'status' => true,
                'Appointments' => $appointments,
            ], http_response_code());
        } else {
            return response([
                'status' => false,
                'message' => 'Can not find Appointments, pls try again',
            ], http_response_code());
        }
    }

    public function appointmentByPaymentStatus(Request $request)
    {
        $appointments = Appointment::where('user_id', Auth::user()->id)
            ->where('payment_status', 'PAID')
            ->with(['doctor', 'zoomMeeting'])
            ->get();

        if ($appointments) {
            // update appointment zoomMeeting status
            foreach ($appointments as $appointment) {

                // get the zoomMeeting start_time and check if the meeting is ongoing, or passed

                $meetingTime = Carbon::parse($appointment->zoomMeeting->start_time);

                $currentTime = Carbon::now();

                $differentInMinutes = $meetingTime->diffInMinutes($currentTime);


                if ($appointment->zoomMeeting->status == 'pending' && $meetingTime->isPast() &&  $differentInMinutes  <= 30) {

                    // if $differentInMinutes is less than 30 minutes, then meeting is ongoing
                    $appointment->zoomMeeting->update([
                        'status' => 'ongoing'
                    ]);
                }

                if ($meetingTime->isPast() && $differentInMinutes >= 30) {

                    // if $differentInMinutes is greater than or equals to 30 minutes, then meeting has passed

                    $appointment->zoomMeeting->update([
                        'status' => 'passed'
                    ]);
                }
            }

            return response([
                'status' => true,
                'Appointments' => $appointments,
            ], http_response_code());
        } else {
            return response([
                'status' => false,
                'message' => 'Can not find Appointments, pls try again',
            ], http_response_code());
        }
    }

    public function findAppointment(Request $request, $id)
    {

        $findAppointment = Appointment::where('unique_id', $id)
            ->where('user_id', Auth::user($request->token)->id)
            ->with('doctor', 'zoomMeeting')
            ->first();

        if ($findAppointment) {
            return response([
                'status' => true,
                'Appointments' => $findAppointment,
            ], http_response_code());
        } else {
            return response([
                'status' => false,
                'message' => 'Can the find Appointment, pls try again',
            ], http_response_code());
        }
    }

    public function rescheduleAppointment(Request $request, $id)
    {
        $this->validate($request, [
            'doctor_unique_id' => 'required',
            'day' => 'required',
            "time_slots.start"  => "required|date_format:H:i",
            "time_slots.end"  => "required|date_format:H:i",
        ]);

        $checkAppointmentBooking = Appointment::where('doctor_unique_id', $request->doctor_unique_id)
            ->where('day', $request->day)
            ->where('start', $request->time_slots['start'])
            ->where('end', $request->time_slots['end'])
            ->first();

        if ($checkAppointmentBooking) {

            return response([
                'status' => false,
                'message' => 'Time slot have already been booked',
            ], 403);
        }

        // find appointment
        $getAppointment = Appointment::where('unique_id', $id)
            ->where('user_id', Auth::user($request->token)->id)->first();

        if (!$getAppointment) {
            return response([
                'status' => false,
                'message' => 'Sorry, Appointment does not exist',
            ], 404);
        }

        $zoom = Zoom::where('appointment_id', $getAppointment->id)->first();

        if (!$zoom) {
            return response([
                'status' => false,
                'message' => 'Sorry, Appointment has not been fully booked',
            ], 404);
        }



        // $updateAppointment = Appointment::where('unique_id', $id)
        //     ->where('user_id', Auth::user($request->token)->id)
        //     ->update(['start' => $request->time_slots['start'], 'end' => $request->time_slots['end']]);

        $updateAppointment =  $getAppointment->update(['start' => $request->time_slots['start'], 'end' => $request->time_slots['end']]);
        // $appointment->user_id = Auth::user($request->token)->id;
        // $appointment->doctor_unique_id = $request->doctor_unique_id;
        // $appointment->day_id = $request->day_id;
        // $appointment->time_slots = json_encode($request->time_slots);
        // $appointment->date = Carbon::now()->toFormattedDateString();
        // $appointment->status = 'successfull';
        // $appointment->payment_status = 'pending';
        // $appointment->payment_reference = '';
        // $appointment->unique_id = Str::random(16);

        if ($updateAppointment) {

            //save db notification
            $user_id = auth()->user()->id;
            $doc_id = $getAppointment->doctor_id;
            $title = 'Appointment Reschedule';
            $message = 'Appointment was Reschedule Successful';
            $usertype = 'Patient';
            $this->sendNotification($user_id, $doc_id, $usertype, $title, $message);

            // cancel the zoom meeting
            $zoom->update([
                'status' => 'cancelled'
            ]);


            return response([
                'status' => true,
                'message' => 'Appointment was Reschedule Successful, use the create meeting API to create a new meeting',
                'Appointments' => $getAppointment,
            ], http_response_code());
        } else {
            return response([
                'status' => false,
                'message' => 'Error rescheduling, pls try again',
            ], http_response_code());
        }
    }

    public function cancelAppointment(Request $request, $id)
    {
        $appointment = Appointment::where('unique_id', $id)
            ->where('user_id', Auth::user($request->token)->id)
            ->first();

        if (!$appointment) {
            return response([
                'status' => false,
                'message' => 'Sorry, Appointment does not exist',
            ], 404);
        }

        $zoom = Zoom::where('appointment_id', $appointment->id)->first();

        if (!$zoom) {
            return response([
                'status' => false,
                'message' => 'Sorry, Appointment has not been fully booked',
            ], 404);
        }

        $appointment->status = "cancelled";

        if ($appointment->save()) {

            $user_id = $appointment->user_id;
            $user = User::find($user_id);
            $appointment->user()->associate($user);

            // $appointment->notify(new CancelAppointmentNotification($appointment));

            // cancel the zoom meeting
            $zoom->update([
                'status' => 'cancelled'
            ]);

            //save db notification
            $doc_id = $appointment->doctor_id;
            $title = 'Appointment Cancelled';
            $message = 'Appointment was cancelled';
            $usertype = 'Patient';
            $this->sendNotification($user_id, $doc_id, $usertype, $title, $message);
        }
    }

    public function sendNotification($authUserId, $receiverId, $usertype, $title, $message)
    {
        //save user notification


        $notification = new Notification();
        $notification->user_id = $authUserId;
        $notification->receiverId = $receiverId;
        $notification->categories = 'Appointment Cancelled';
        $notification->user_type = $usertype;
        $notification->title = $title;
        $notification->message = $message;
        if ($notification->save()) {
            return true;
        }
        return false;
    }

    public function updateAppointment(Request $request) // update appointment status after patient meeting with doctor
    {
        $appointment = Appointment::where('id', $request->appointment_id)
            ->where('user_id', Auth::user($request->token)->id)
            ->first();

        if (!$appointment) {
            return response([
                'status' => false,
                'message' => 'Sorry, Appointment does not exist',
            ], 404);
        }

        $data = [
            'doctor_id' => $appointment->doctor_id,
            'day' => $appointment->day,
        ];

        $appointment->status = $request->status;

        if ($appointment->save()) {

            // update zoom meeting
            $zoom = Zoom::where('appointment_id', $appointment->id)->first();

            if (!$zoom) {
                return response([
                    'status' => false,
                    'message' => 'Sorry, Appointment has not been fully booked',
                ], 404);
            }

            $zoom->update([
                'status' => $request->status
            ]);

            $doctor_id = $data['doctor_id'];
            $date = $data['date'];
            $time_slots = [
                "start" =>  $appointment->start_time,
                "end" =>  $appointment->end_time,
            ];
            return UpdateTimeslotStatus::run($doctor_id, $date, $time_slots);

            // update time slot for this appointment


            $user_id = $appointment->user_id;
            $user = User::find($user_id);
            $appointment->user()->associate($user);

            // $appointment->notify(new CancelAppointmentNotification($appointment));

            //save db notification
            $doc_id = $appointment->doctor_id;
            $title = 'Appointment Completed';
            $message = 'Appointment was completed';
            $usertype = 'Patient';
            $this->sendNotification($user_id, $doc_id, $usertype, $title, $message);
        }
    }

    public function viewAppointmentReport(Request $request, $id)
    {
        $appointmentReport = Report::whereUserId(Auth::user()->id)->where('appointment_id', $id)->first();

        if (!$appointmentReport) {
            return response([
                'status' => false,
                'message' => 'Sorry, Appointment Report does not exist',
            ], 404);
        }

        return response([
            'status' => true,
            'message' => 'Appointment Report retrieved successfully',
            'data' => $appointmentReport,
        ], 200);
    }


    function getTimeSlot($interval, $start_time, $end_time)
    {
        // $start = new DateTime($start_time);
        // $end = new DateTime($end_time);
        // $startTime = $start->format('H:i');
        // $endTime = $end->format('H:i');
        // $i = 0;
        // $time = [];
        // while (strtotime($startTime) <= strtotime($endTime)) {
        //     $start = $startTime;
        //     $end = date('H:i', strtotime('+' . $interval . ' minutes', strtotime($startTime)));
        //     $startTime = date('H:i', strtotime('+' . $interval . ' minutes', strtotime($startTime)));
        //     $i++;
        //     if (strtotime($startTime) <= strtotime($endTime)) {
        //         $time[$i]['slot_start_time'] = $start;
        //         $time[$i]['slot_end_time'] = $end;
        //     }
        // }
        // return $time;
    }

    //     $slots = getTimeSlot(30, '10:00', '13:00');
    // echo '<pre>';
    // print_r($slots);
}
