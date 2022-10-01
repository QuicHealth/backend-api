<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $report = Report::where('doctor_id', auth('doctoer')->user()->id);

        return response([
            'status' => true,
            'msg' => 'Report retrieved successfully',
            'data' => $report
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'description' => 'required',
        ]);

        $report = Report::create([
            'doctore_id' => auth('doctor')->user()->id,
            'user_id' => 1,
            'appointments_id' => 1,
            'description' => $request->description,
        ]);

        return response([
            'status' => true,
            'msg' => 'Report saved successfully',
            'data' => $report
        ]);
    }
}
