<?php

namespace App\Services;

use DateTime;
use Carbon\Carbon;
use App\Models\Zoom;
use App\Traits\ZoomTrait;
use App\Models\Appointment;
use Illuminate\Support\Str;

class ZoomServices
{
    use ZoomTrait;

    public function create(array $data)
    {
        $getappointment = Appointment::where('id', $data['appointment_id'])
            ->where('user_id', auth()->user()->id)
            ->where('payment_status', 'PAID')
            ->first();

        if (!$getappointment) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, you have not Paid this appointment',
                'data' => []
            ], 422);
        }

        $checkZoom = Zoom::where('appointment_id', $data['appointment_id'])->first();

        if ($checkZoom !== null) {
            if ($checkZoom->status !== 'cancelled') {
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry, meeting already created for this appointment',
                    'data' => []
                ], 422);
            }
        }

        $appointmentTime = $getappointment->date . '' . $getappointment->start;

        $tempDate = new DateTime($appointmentTime);

        $startTime = $tempDate->format('Y-m-d\TH:i:s');

        $meetingData = [
            'topic' => $data['topic'],
            'agenda' => $data['agenda'],
            'duration' => $data['duration'],
            'password' => Str::random(6),
            'start_time' => $startTime,
            // 'start_time' => '2022-09-01T12:40:26Z',
            'timezone' => 'West Central Africa',
            'pre_schedule' => false,
            'meeting_invitees' => [
                'email' => auth()->user()->email,
            ],
            "settings" => [
                "encryption_type" => "enhanced_encryption",
                "focus_mode" => true,
                "host_video" => true,
                "jbh_time" => 0,
                "join_before_host" => true,
            ],
        ];

        try {

            $meeting = $this->createMeeting($meetingData);

            if ($meeting['status'] == true) {

                $start_at = new Carbon($meeting['data']['start_time']);

                $create = Zoom::create([
                    'user_id' => $getappointment->user_id,
                    'doctor_id' => $getappointment->doctor_id,
                    'appointment_id' => $getappointment->id,
                    'meeting_id' => $meeting['data']['id'],
                    'topic' => $meeting['data']['topic'],
                    'start_at' => $start_at->format('Y-m-d H:i:s'),
                    'duration' => $meeting['data']['duration'],
                    'password' => $meeting['data']['password'],
                    'start_url' => $meeting['data']['start_url'],
                    'join_url' => $meeting['data']['join_url'],
                    'status' => 'pending'
                ]);

                if ($create) {
                    return response()->json([
                        'status' => true,
                        'message' => 'success, meeting created',
                        'data' => $create
                    ], 200);
                }

                return response()->json([
                    'status' => false,
                    'message' => 'error, can not save meeting',
                ], 422);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'can not create the meeting',
                ], 422);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function refreshToken()
    {
        return $this->refresh_token();
    }
}