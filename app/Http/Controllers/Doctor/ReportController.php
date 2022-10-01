<?php

namespace App\Http\Controllers\Doctor;

use App\Models\Report;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{

    public function healthRecord()
    {
        $healthRecord = Report::where('user_id', auth()->user()->id);

        if ($healthRecord) {
            return response()->json([
                'status' => 'success',
                'data' => $healthRecord
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No health record found'
            ]);
        }
    }

    public function store(Request $request, $appointment_id)
    {

        $this->validate($request, [
            'description' => 'required',
        ]);

        // get appointment
        $appointment = Appointment::find($appointment_id);

        if ($appointment) {
            $report = Report::create([
                'doctore_id' => auth('doctor')->user()->id,
                'user_id' => $appointment->user_id,
                'appointments_id' => $appointment->id,
                'description' => $request->description,
            ]);

            if ($report) {
                return response([
                    'status' => true,
                    'msg' => 'Report created successfully',
                    'data' => $report
                ]);
            }
        }
    }
}
