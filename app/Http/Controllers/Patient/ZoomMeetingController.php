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
        $meeting = zoom::all();
        if($meeting)
        {
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $meeting
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $getappint = Appointment::where('user_id', auth()->user()->id)->where('payment_status', 'PAID')->first();

        if(!$getappint)
        {
            return response()->json([
                'status' => false,
                'message' => 'error',
                'data' => []
            ], 422);
        }
        $meeting = $this->createMeeting($request);

        $create = zoom::create([
            'appointment_id' => $getappint->id,
            'meeting_id' => $meeting->id,
            'topic' => $request->topic,
            'start_at' => new Carbon($getappint->start),
            'duration' => $meeting->duration,
            'password' => $meeting->password,
            'start_url' => $meeting->start_url,
            'join_url' => $meeting->join_url,
        ]);

        // Test code//
        // $create = zoom::create([
        //     'appointment_id' => $request->appointment_id,
        //     'meeting_id' => $meeting->id,
        //     'topic' => $request->topic,
        //     'start_at' => new Carbon($request->start),
        //     'duration' => $meeting->duration,
        //     'password' => $meeting->password,
        //     'start_url' => $meeting->start_url,
        //     'join_url' => $meeting->join_url,
        // ]);

        if($create)
        {
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $create
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'error',
            'data' => []
        ], 422);
    }
}
