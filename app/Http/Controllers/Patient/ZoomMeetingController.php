<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Zoom;
use App\Traits\ZoomMeetingTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ZoomMeetingController extends Controller
{
    use ZoomMeetingTrait;

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

    public function getMeetingsByDoctor()
    {
        $meetings = zoom::where('doctor_id', auth()->user()->id)->get();

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

    public function store(Request $request)
    {
        $getappint = Appointment::where('user_id', auth()->user()->id)->where('payment_status', 'PAID')->first();
        // dd($getappint);
        if (!$getappint) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, you have not Paid this appointment',
                'data' => []
            ], 422);
        }
        $meeting = $this->createMeeting($request);

        $create = zoom::create([
            'user_id' => $getappint->user_id,
            'doctor_id' => $getappint->doctor_id,
            'appointment_id' => $getappint->id,
            'meeting_id' => $meeting->id,
            'topic' => 'Meeting with QuicHealth Doctor',
            'start_at' => new Carbon($getappint->start),
            'duration' => $meeting->duration,
            'password' => $meeting->password,
            'start_url' => $meeting->start_url,
            'join_url' => $meeting->join_url,
        ]);

        if ($create) {
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $create
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'can not create',
        ], 422);
    }
}