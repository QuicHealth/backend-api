<?php

namespace App\Http\Controllers\Patient;

use Carbon\Carbon;
use App\Models\Zoom;
use App\Traits\ZoomTrait;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ZoomMeetingController extends Controller
{
    use  ZoomTrait;

    public function index()
    {
        $meetings = zoom::where('user_id', auth()->user()->id)->get();

        if ($meetings) {
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $meetings
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'error',
            'data' => []
        ], 422);
    }

    public function getZoomUrl()
    {
        // dd($this->getAuthUrl());
        return redirect()->to($this->getAuthUrl());
    }

    public function redirect(Request $request)
    {
        $code = $request->code;
        return $this->getToken($code);
    }

    public function refreshToken()
    {
        return $this->refresh_token();
    }

    public function createZoomMeeting(Request $request)
    {
        $getappint = Appointment::where('id', $request->appointment_id)
            ->where('user_id', auth()->user()->id)
            ->where('payment_status', 'PAID')
            ->first();

        if (!$getappint) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, you have not Paid this appointment',
                'data' => []
            ], 422);
        }

        $appointmentTime = $getappint->date . '' . $getappint->start;
        $formatedAppointmentTime = Carbon::parse($appointmentTime)->toIso8601ZuluString();
        // dd($formatedAppointmentTime);
        $data = [
            'topic' => $request->topic,
            'agenda' => $request->agenda,
            'duration' => $request->duration,
            'password' => Str::random(6),
            'start_time' => $formatedAppointmentTime,
            'timezone' => 'West Central Africa',
            'pre_schedule' => false,
            'meeting_invitees' => [
                'email' => auth()->user()->email,
            ],
            "settings" => [
                "alternative_hosts" => "dikep15@gmail.com",
                "encryption_type" => "enhanced_encryption",
                "focus_mode" => true,
                "host_video" => true,
                "jbh_time" => 0,
                "join_before_host" => true,
                "private_meeting" => true,
            ],
        ];

        try {

            $meeting = $this->createMeeting($data);
            return response()->json([$meeting], 200);
            // if ($meeting) {

            //     $start_at = new Carbon($meeting['data']['start_time']);

            //     $create = Zoom::create([
            //         'user_id' => $getappint->user_id,
            //         'doctor_id' => $getappint->doctor_id,
            //         'appointment_id' => $getappint->id,
            //         'meeting_id' => $meeting['data']['id'],
            //         'topic' => $meeting['data']['topic'],
            //         'start_at' => $start_at->format('Y-m-d H:i:s'),
            //         'duration' => $meeting['data']['duration'],
            //         'password' => $meeting['data']['password'],
            //         'start_url' => $meeting['data']['start_url'],
            //         'join_url' => $meeting['data']['join_url'],
            //     ]);

            //     if ($create) {
            //         return response()->json([
            //             'status' => true,
            //             'message' => 'success',
            //             'data' => $create
            //         ], 200);
            //     }

            //     return response()->json([
            //         'status' => false,
            //         'message' => 'error, can not save meeting',
            //     ], 422);
            // } else {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'can not create',
            //     ], 422);
            // }
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function getMeetingsByPatient()
    {
        $meetings = zoom::where('user_id', auth()->user()->id)->get();

        if ($meetings) {
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $meetings
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'error',
            'data' => []
        ], 422);
    }
}