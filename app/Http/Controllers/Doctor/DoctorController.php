<?php

namespace App\Http\Controllers\Doctor;

use App\Models\Zoom;
use App\Models\Doctor;
use App\Models\Details;
use App\Models\Schedule;
use App\Models\Appointment;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Services\SettingService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\SetAvailablityAction;
use App\Http\Requests\ScheduleRequest;
use App\Http\Requests\SettingsRequest;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\ScheduleResource;

class DoctorController extends Controller
{
    protected $service;

    public function __construct()
    {
        if (auth('doctor_api')->check()) {
            $this->service = new SettingService(new Doctor, auth('doctor_api')->user()->id);
        }
    }

    public function setSchedule(ScheduleRequest $request)
    {
        $validated = $request->validated();

        return SetAvailablityAction::run($validated);
    }

    public function getSchedule()
    {
        $schedule = Schedule::where('doctor_id', auth('doctor_api')->user()->id)->with('timeslot')->get();
        return response([
            'status' => true,
            'data' => $schedule
        ]);
    }

    public function searchSchedule($date)
    {
        $schedule = Schedule::where('doctor_id', auth('doctor_api')->user()->id)
            ->where('date', $date)
            ->with('timeslot')
            ->get();

        return response([
            'status' => true,
            'scheduling' =>  ScheduleResource::collection($schedule)
        ]);
    }


    public function getDoctorsDashboard(Request $request)
    {
        // $doctor = Doctor::where("id", auth('doctor_api')->user()->id)
        //     ->with(['hospital', 'schedule', 'appointments'])
        //     ->first();
        $totalPendingAppointments  = Appointment::where('user_id', auth('doctor_api')->user()->id)->where('status', 'pending')->count();
        $totalSuccussfulAppointments  = Appointment::where('user_id', auth('doctor_api')->user()->id)->where('status', 'successful')->count();
        $allPendingAppointments = Appointment::where('user_id', auth('doctor_api')->user()->id)->get();

        // dd($doctor);DoctorResource::collection
        return response([
            'status' => true,
            // 'doctor' => new DoctorResource($doctor),
            'totalPendingAppointments' => $totalPendingAppointments,
            'totalSuccussfulAppointments' => $totalSuccussfulAppointments,
            'allPendingAppointments' => $allPendingAppointments
        ]);

        // return response()->json(['success' => true, 'doctor' => new DoctorResource($doctor)]);
    }

    public function testDoctor(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:doctors',
            'phone' => 'required',
            'address' => 'required|string',
            'specialty' => 'required|integer',
            'password' => 'required|min:6',
        ]);

        // $password = rand(111111, 999999);


        $hos = new Doctor();
        $hos->name = $request->name;
        $hos->phone = $request->phone;
        $hos->password = bcrypt($request->password);
        $hos->email = $request->email;
        $hos->unique_id = uniqid();
        $hos->address = $request->address;
        $hos->specialty = $request->specialty;
        $hos->hospital_id = $request->hospital_id;

        if (!$hos->save()) {
            return response([
                'status' => true,
                'msg' => 'Error saving doctor'
            ]);
        }

        return response([
            'status' => true,
            'msg' => 'Doctor saved successfully',
            'data' => $hos
        ]);
    }

    public function getMeetingsByDoctor()
    {
        $meetings = Zoom::where('doctor_id', auth('doctor_api')->user()->id)->get();

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

    public function appointmentByPaymentStatus(Request $request)
    {
        $appointments = Appointment::where('doctor_id', Auth::user($request->token)->id)
            ->where('payment_status', 'PAID')
            ->with(['user', 'zoomMeeting'])
            ->get();

        if ($appointments) {
            return response([
                'status' => true,
                'Appointments' => $appointments,
            ], http_response_code());
        } else {
            return response([
                'status' => false,
                'message' => 'You dont have any appointments',
            ], http_response_code());
        }
    }

    public function getAppointmentDetail($appointment_id)
    {
        $details = Details::where('appointment_id', $appointment_id)->first();

        if ($details) {
            return response([
                'status' => true,
                'details' => $details,
            ], http_response_code());
        } else {
            return response([
                'status' => false,
                'message' => 'No details found',
            ], http_response_code());
        }
    }

    public function getsetting(): array
    {
        return  $this->service->settings()->get();
    }

    public function updateSetting(SettingsRequest $request)
    {
        $validated = $request->validated();

        $cloundinaryFolder = "";

        if ($request->hasfile('image')) {

            $validated['image']  = $request->file('image')->getRealPath();
            $cloundinaryFolder = "doctor";
        }

        return $this->service->settings()->saveUpdate($validated, $cloundinaryFolder);
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        return $this->service->settings()->saveUpdatePassword($request->all());
    }

    public function allNotification()
    {
        $notification = Notification::where('user_type', 'doctor')
            ->where('user_id', auth('doctor_api')->user()->id)
            ->get();
        if ($notification) {
            return response()->json([
                'status' => true,
                'message' => $notification
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 401);
        }
    }

    public function updateNotification($id)
    {
        $notification = Notification::where('user_type', 'doctor')
            ->where('userId', auth('doctor_api')->user()->id)
            ->where('id', $id)
            ->firstOrFail();
        $notification->doctorRead = true;

        if ($notification->save()) {
            return response()->json([
                'status' => true,
                'message' => $notification
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 401);
        }
    }
}