<?php

namespace App\Http\Controllers\Doctor;

use App\Models\Zoom;
use App\Models\Doctor;
use App\Models\Report;
use App\Models\Account;
use App\Models\Details;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\DoctorProfile;
use Illuminate\Validation\Rule;
use App\Services\SettingService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\SetAvailablityAction;
use App\Services\NotificationService;
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
        $schedule = Schedule::where('doctor_id',  auth('doctor_api')->user()->id)
            ->where('availablity', true)
            ->whereRelation('timeslot', 'availablity', true)
            ->get();

        return response([
            'status' => true,
            'data' => $schedule
        ]);
    }

    public function searchSchedule($date)
    {
        $schedule = Schedule::where('doctor_id', auth('doctor_api')->user()->id)
            ->where('date', $date)
            ->where('availablity', true)
            ->whereRelation('timeslot', 'availablity', true)
            ->get();

        return response([
            'status' => true,
            'scheduling' =>  ScheduleResource::collection($schedule)
        ]);
    }

    public function getDoctorsDashboard(Request $request)
    {
        $doctor = Doctor::where("id", auth('doctor_api')->user()->id)
            ->with(['hospital', 'schedule', 'appointments'])
            ->first();
        $totalPendingAppointments  = Appointment::where('doctor_id', auth('doctor_api')->user()->id)->where('status', 'pending')->count();
        $totalSuccussfulAppointments  = Appointment::where('doctor_id', auth('doctor_api')->user()->id)->where('status', 'successful')->count();
        // $allPendingAppointments = Appointment::where('user_id', auth('doctor_api')->user()->id)->with('doctor')->get();

        // dd($doctor);DoctorResource::collection
        return response([
            'status' => true,
            'doctor' => new DoctorResource($doctor),
            'totalPendingAppointments' => $totalPendingAppointments,
            'totalSuccussfulAppointments' => $totalSuccussfulAppointments,
            // 'allPendingAppointments' => $allPendingAppointments
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

    public function findAppointment($unique_id)
    {

        $appointments = Appointment::whereUniqueId($unique_id)
            ->with(['user', 'user.healthProfile', 'zoomMeeting', 'details'])
            ->first();

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

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {
            // upload to cloudinary

            $imageFile  = $request->file('image');
            $folder = 'doctor';

            $upload =  $this->service->settings()->uploadImage($imageFile, $folder);

            if ($upload['status'] == true) {
                return response()->json([
                    'status' => "success",
                    'message' => $upload['message'],
                ]);
            } else {
                return response()->json([
                    'status' => "error",
                    'message' =>  $upload['message'],
                ]);
            }
        } else {
            return response()->json([
                'status' => "Failed",
                'message' => 'No image found',
            ]);
        }
    }

    public function removeImage()
    {
        $removeImage =  Doctor::where('id', auth('doctor_api')->user()->id)->update(['profile_pic_link' => null]);

        if ($removeImage) {
            return response()->json([
                'status' => 'success',
                'message' => 'Image removed successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Error removing image'
            ]);
        }
    }

    public function recordHealthHistory(Request $request)
    {
        $this->validate($request, [
            'diagnosis' => 'sometimes',
            'treatments' => 'sometimes',
            'user_id' => 'required',
            'appointment_id' => 'required'
        ]);

        $record = new Report();
        $record->doctor_id = auth('doctor_api')->user()->id;
        $record->user_id = $request->user_id;
        $record->appointments_id = $request->appointment_id;
        $record->diagnosis = $request->diagnosis;
        $record->treatments = $request->treatments;

        if ($record->save()) {
            return response([
                'status' => true,
                'msg' => 'Health history recorded successfully',
                'data' => $record
            ]);
        }

        return response([
            'status' => false,
            'msg' => 'Error recording health history'
        ]);
    }

    public function history()
    {
        // $healthRecord = Report::where('doctor_id', auth('doctor_api')->user()->id)->with('appointments')->get();
        // $detail = Details::where('appointment_id', auth('doctor_api')->user()->id)->with('appointments')->get();
        $appointmentHistory = Appointment::where('doctor_id', auth('doctor_api')->user()->id)->with('report', 'details')->get();

        if ($appointmentHistory) {
            return response()->json([
                'status' => 'success',
                'data' => $appointmentHistory
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No health record found'
            ]);
        }
    }

    public function getEMR($appointment_id)
    {
        $record = Report::where('appointments_id', $appointment_id)->first();

        if ($record) {
            return response([
                'status' => true,
                'message' => 'Health history found',
                'data' => $record
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Health history not found'
        ]);
    }

    public function UpdateEMR(Request $request, $appointment_id)
    {
        try {
            $this->validate($request, [
                'diagnosis' => 'sometimes',
                'treatments' => 'sometimes',
            ]);

            $update = Report::updateOrCreate(['appointments_id' => $appointment_id], [
                'diagnosis' => $request->diagnosis,
                'treatments' => $request->treatments
            ]);

            if ($update) {
                return response([
                    'status' => true,
                    'message' => 'ERM updated successfully'
                ]);
            }
        } catch (\Throwable $e) {

            return response([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        return $this->service->settings()->saveUpdatePassword($request->all());
    }

    public function getAllNotification()
    {
        $user_type = "doctor";

        $getAllNotifications = new NotificationService($user_type, auth('doctor_api')->user()->id);

        return $getAllNotifications->notifications()->all();
    }

    public function markNotificationAsRead(Request $request)
    {
        $user_type = "doctor";

        $notification_id = $request->notification_id ?? '';

        $markAsRead = new NotificationService($user_type, auth('doctor_api')->user()->id);

        return $markAsRead->notification($notification_id)->update();
    }

    public function account(Request $request)
    {
        $doctor_id = auth('doctor_api')->user()->id;
        $this->validate($request, [
            'bank' => ['required', 'string'],
            'account_name' => ['required', 'string', 'min:3'],
            'account_number' => ['required', 'numeric', Rule::unique('accounts')->ignore($doctor_id)],
            'amount' => ['required', 'numeric'],
        ]);

        // using laraval updateOrCreate() method
        $account = Account::updateOrCreate(
            ['doctor_id' => auth('doctor_api')->user()->id],
            [
                'bank' => $request->bank,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'amount' => $request->amount,
            ]
        );

        if ($account) {
            return response([
                'status' => true,
                'message' => 'Account details saved successfully',
                'data' => $account
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Error saving account Details'
        ]);
    }

    public function getAccountDetails()
    {
        $account = Account::where('doctor_id', auth('doctor_api')->user()->id)->first();

        if ($account) {
            return response([
                'status' => true,
                'message' => 'Account details found',
                'data' => $account
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Account details not found'
        ]);
    }

    public function healthProfile(Request $request)
    {
        $hospital = Doctor::find(auth('doctor_api')->user()->id)->hospital;

        $this->validate($request, [
            'bio' => ['required', 'string'],
            'fee' => ['required', 'numeric'],
        ]);

        // using laraval updateOrCreate() method
        $profile = DoctorProfile::updateOrCreate(
            ['doctor_id' => auth('doctor_api')->user()->id],
            [
                'bio' => $request->bio,
                'fee' => $request->fee,
                'hospital' => $hospital->name,
            ]
        );

        if ($profile) {
            return response([
                'status' => true,
                'message' => 'Health Profile details saved successfully',
                'data' => $profile
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Error saving Health Profile Details'
        ]);
    }

    public function getHealthProfile()
    {
        $profile = DoctorProfile::where('doctor_id', auth('doctor_api')->user()->id)->first();

        if ($profile) {
            return response([
                'status' => true,
                'message' => 'Health Profile details found',
                'data' => $profile
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Health Profile details not found'
        ]);
    }
}
