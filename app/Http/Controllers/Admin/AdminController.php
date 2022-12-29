<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Jobs\MailSendingJob;
use Illuminate\Http\Request;
use App\Mail\newHospitalMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\helpController;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Payment;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        if (Auth::guard('admin')->check() == false) {
            return redirect('/admin/login');
        }
    }
    public function index()
    {
        $data = [];

        $data['users_count'] = User::count();
        $data['hospitals_count'] = Hospital::count();
        $data['doctors_count'] = Doctor::count();

        return view('admins.home', ['data' => $data]);
    }

    public function notfound()
    {
        return view('errors.404');
    }

    public function users()
    {
        $users = User::all();
        return view('admins.user.index')->with('users', $users);
    }

    public function userId($unique_id)
    {
        $user = User::where("unique_id", $unique_id)->first();
        return view('admins.user.details')->with('user', $user);
    }

    public function addUser(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric|unique:users',
            'address' => 'required|string',
            'city' => 'required|string',
            'gender' => 'required'
        ]);

        $password = "password";
        // $data = [
        //     'email' => $request->email,
        //     'subject' => 'Welcome To QuicHealth',
        //     'name' => 'Welcome',
        //     'view' => 'mail.mail',
        //     'content' =>
        //     'Welcome to QuicHealth, we are happy to have you here. Your profile has been created, below are login details. Login and update your profile <br>
        //         First Name: ' .
        //         $request->firstname .
        //         'Last Name: ' .
        //         $request->lastname .
        //         '<br> Email: ' .
        //         $request->email .
        //         '<br> Password: ' .
        //         $password .
        //         '<br> Phone: ' .
        //         $request->phone .
        //         '<br> Address: ' .
        //         $request->address .
        //         '<br> City: ' .
        //         $request->city .
        //         '<br> Gender: ' .
        //         $request->gender,
        // ];

        // MailSendingJob::dispatch($data);

        $hos = new User();
        $hos->firstname = $request->firstname;
        $hos->lastname = $request->lastname;
        $hos->email = $request->email;
        $hos->phone = $request->phone;
        $hos->unique_id = uniqid();
        $hos->address = $request->address;
        $hos->city = $request->city;
        $hos->gender = $request->gender;
        $hos->password = bcrypt($password);

        if (!$hos->save()) {
            helpController::flashSession(false, 'Error saving User');
            return back();
        }
        helpController::flashSession(true, 'User saved successfully');
        return back();
    }

    public function updateUser($unique_id, Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required|string',
            'city' => 'required|string',
            'gender' => 'required'
        ]);

        $hos = User::where("unique_id", $unique_id)->first();
        if (!$hos) {
            helpController::flashSession(false, 'User not found');
            return redirect('admin/user');
        }
        $hos->firstname = $request->firstname;
        $hos->lastname = $request->lastname;
        $hos->email = $request->email;
        $hos->phone = $request->phone;
        $hos->address = $request->address;
        $hos->city = $request->city;
        $hos->gender = $request->gender;

        if ($request['longitude']) {
            $hos->longitude = $request['longitude'];
        }
        if ($request['latitude']) {
            $hos->latitude = $request['latitude'];
        }

        if (!$hos->save()) {
            helpController::flashSession(false, 'Error updating user');
            return back();
        }
        helpController::flashSession(true, 'User updated successfully');
        return back();
    }

    public function deleteUser($unique_id)
    {
        $user = User::where("unique_id", $unique_id)->first();
        if ($user->delete()) {
            helpController::flashSession(true, 'User deleted successfully');
            return back();
        }
    }

    public function verifyHospital()
    {
        // $hos = Hospital::all();
        $unverified_hospitals =  Hospital::where('verified', false)->get();

        return view('admins.hospital.verify', ['unverified_hospitals' => $unverified_hospitals]);
    }

    public function hospitals()
    {
        $hos = Hospital::all();
        // return view('admin.hospital.index', ['hospitals' => $hos]);
        return view('admins.hospital.index', ['hos' => $hos]);
    }

    public function hospital($unique_id)
    {
        $hos = Hospital::where("unique_id", $unique_id)->first();
        if (!$hos) {
            helpController::flashSession(false, 'Hospital not found');
            return redirect('admin/hospital');
        }
        return view('admins.hospital.details', ['hos' => $hos]);
        // return view('admin.hospital.hospital', ['hospital' => $hos]);
    }

    public function addHospital(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:hospitals',
            'email' => 'required|email|unique:hospitals',
            'phone' => 'required|numeric|unique:hospitals',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
        ]);

        //  $password = rand(111111, 999999);
        $password = "password";
        // $data = [
        //     'email' => $request->email,
        //     'subject' => 'Welcome To QuicHealth',
        //     'name' => 'Welcome',
        //     'view' => 'mail.mail',
        //     'content' =>
        //     'Welcome to QuicHealth, we are happy to have you here. Your hospital profile has been created, below are login details. Login and update your profile <br>
        //         Name: ' .
        //         $request->name .
        //         '<br> Email: ' .
        //         $request->email .
        //         '<br> Password: ' .
        //         $password .
        //         '<br> Phone: ' .
        //         $request->phone .
        //         '<br> Address: ' .
        //         $request->address .
        //         '<br> City: ' .
        //         $request->city .
        //         '<br> State: ' .
        //         $request->state .
        //         '<br> Country: ' .
        //         $request->country,
        // ];

        // MailSendingJob::dispatch($data);

        $hos = new Hospital();
        $hos->name = $request->name;
        $hos->email = $request->email;
        $hos->phone = $request->phone;
        $hos->unique_id = uniqid();
        $hos->address = $request->address;
        $hos->city = $request->city;
        $hos->state = $request->state;
        $hos->password = bcrypt($password);
        $hos->country = $request->country;

        if ($request['longitude']) {
            $hos->longitude = $request['longitude'];
        }
        if ($request['latitude']) {
            $hos->latitude = $request['latitude'];
        }

        if (!$hos->save()) {
            helpController::flashSession(false, 'Error saving hospital');
            return back();
        }
        helpController::flashSession(true, 'Hospital saved successfully');
        return back();
    }

    public function approveHospital($unique_id)
    {

        $hospital = Hospital::where("unique_id", $unique_id)->first();
        if (!$hospital) {
            helpController::flashSession(false, 'Hospital not found');
            return redirect('admin/hospital/verify');
        }

        $hospital->verified = true;

        // create a unique code


        if (!$hospital->save()) {
            helpController::flashSession(false, 'Error approving hospital');
            return back();
        }
        helpController::flashSession(true, 'Hospital approved successfully');
        return back();
    }

    public function updateHospital($unique_id, Request $request)
    {
        $this->validate($request, [
            // 'hospital' => 'required|integer',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
        ]);

        $hos = Hospital::where("unique_id", $unique_id)->first();
        if (!$hos) {
            helpController::flashSession(false, 'Hospital not found');
            return redirect('admin/hospital');
        }
        $hos->name = $request->name;
        $hos->email = $request->email;
        $hos->phone = $request->phone;
        $hos->address = $request->address;
        $hos->city = $request->city;
        $hos->state = $request->state;
        $hos->country = $request->country;

        if ($request['longitude']) {
            $hos->longitude = $request['longitude'];
        }
        if ($request['latitude']) {
            $hos->latitude = $request['latitude'];
        }

        if (!$hos->save()) {
            helpController::flashSession(false, 'Error updating hospital');
            return back();
        }
        helpController::flashSession(true, 'Hospital updated successfully');
        return back();
    }

    public function deleteHospital($unique_id)
    {
        $hos = Hospital::where("unique_id", $unique_id)->first();
        if (!$hos) {
            helpController::flashSession(false, 'Hospital not found');
            return redirect('admin/hospital');
        }

        if (!$hos->delete()) {
            helpController::flashSession(false, 'Error deleting hospital');
            return back();
        }
        helpController::flashSession(false, 'Hospital deleted successfully');
        return redirect('admin/hospital');
    }

    public function doctors()
    {
        $doc = Doctor::get();
        $hos = Hospital::all();
        // $spec = DB::table('specialties')->get();
        // dd($doc);
        // return view('admin.doctor.index', ['doc' => $doc, 'hos' => $hos, 'spec' => $spec]);
        return view('admins.doctor.index', ['doc' => $doc, 'hos' => $hos]);
    }

    public function doctor($unique_id)
    {
        $doc = Doctor::where("unique_id", $unique_id)->first();
        $spec = DB::table('specialties')->get();
        $hos = Hospital::all();
        if (!$doc) {
            helpController::flashSession(false, 'Doctor not found');
            return redirect('admin/doctor');
        }
        // return view('admin.doctor.doctor', ['doctor' => $doc, 'specialties' => $spec, 'hospitals' => $hos]);
        return view('admins.doctor.details', ['doc' => $doc, 'specialties' => $spec, 'hos' => $hos]);
    }

    public function addDoctor(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:doctors',
            'hospital' => 'required',
            'phone' => 'required|numeric',
            'address' => 'required|string',
            // 'specialty' => 'required|integer',
        ]);


        $password = "password";

        $hos = new Doctor();
        $hos->name = $request->name;
        $hos->phone = $request->phone;
        $hos->password = bcrypt($password);
        $hos->email = $request->email;
        $hos->unique_id = uniqid();
        $hos->address = $request->address;
        $hos->specialty = $request->specialty;
        $hos->hospital_id = $request->hospital;

        if (!$hos->save()) {
            helpController::flashSession(false, 'Error saving doctor');
            return back();
        }
        // $data = [
        //     'email' => $request->email,
        //     'subject' => 'Welcome To QuicHealth',
        //     'name' => 'Welcome',
        //     'view' => 'mail.mail',
        //     'content' =>
        //     'Hi ' . $request->name .
        //         '<br> Welcome to Quichealth, we are happy to have you here. Your doctor profile has been created, below are profile details <br>
        //         Name: ' .
        //         $request->name .
        //         '<br> Email: ' .
        //         $request->email .
        //         '<br> Hospital: ' .
        //         $hos->hospital->name .
        //         '<br> Password: ' .
        //         $password .
        //         '<br> Phone: ' .
        //         $request->phone .
        //         '<br> Address: ' .
        //         $request->address,
        // ];
        // MailSendingJob::dispatch($data);

        helpController::flashSession(true, 'Doctor saved successfully');
        return back();
    }

    public function updateDoctor($unique_id, Request $request)
    {
        $this->validate($request, [
            // 'doctor' => 'required|integer',
            'hospital' => 'required',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            // 'specialty' => 'required|integer',
            'address' => 'required|string',
            'city' => 'required|string',
        ]);

        $hos = Doctor::where("unique_id", $unique_id)->first();
        if (!$hos) {
            helpController::flashSession(false, 'Doctor not found');
            return redirect('admin/doctors');
        }
        $hos->name = $request->name;
        $hos->email = $request->email;
        $hos->address = $request->address;
        $hos->phone = $request->phone;
        $hos->hospital_id = $request->hospital;
        $hos->city = $request->city;

        if (!$hos->save()) {
            helpController::flashSession(false, 'Error updating doctor');
            return back();
        }
        helpController::flashSession(true, 'Doctor updated successfully');
        return back();
    }

    public function deleteDoctor(Request $request)
    {
        $this->validate($request, [
            'doctor' => 'required|integer',
        ]);

        $hos = Doctor::where("unique_id", $request->doctor)->first();
        if (!$hos) {
            helpController::flashSession(false, 'Doctor not found');
            return redirect('admin/doctors');
        }

        if (!$hos->delete()) {
            helpController::flashSession(false, 'Error deleting doctor');
            return back();
        }
        helpController::flashSession(false, 'Doctor deleted successfully');
        return redirect('admin/doctors');
    }

    public function user($id)
    {
        $user = User::find($id);
        if (!$user) {
            HelpController::flashSession(false, 'User not found');
            return redirect('admin/users');
        }
        return view('admin.users.user')->with('user', $user);
    }

    public function payment()
    {
        $payments = Payment::all();
        return view('admins.financial.payment', compact('payments'));
    }

    public function hospitalPayout()
    {
        return view('admins.financial.hospitalPayout');
    }

    public function sendEmail()
    {
        return view('admins.sendEmail');
    }

    public function complains()
    {
        return view('admins.complains');
    }

    public function messages()
    {
        return view('admins.messages');
    }

    public function admins()
    {
        $admins = Admin::all();
        return view('admins.admins.index', compact('admins'));
    }

    public function addAdmin(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric|unique:users',
            'address' => 'required|string',
            'city' => 'required|string',
            'gender' => 'required'
        ]);

        $password = rand(111111, 999999);
        $data = [
            'email' => $request->email,
            'subject' => 'Welcome To QuicHealth',
            'name' => 'Welcome',
            'view' => 'mail.mail',
            'content' =>
            'Welcome to QuicHealth, we are happy to have you here. Your profile has been created, below are login details. Login and update your profile <br>
                First Name: ' .
                $request->firstname .
                'Last Name: ' .
                $request->lastname .
                '<br> Email: ' .
                $request->email .
                '<br> Password: ' .
                $password .
                '<br> Phone: ' .
                $request->phone .
                '<br> Address: ' .
                $request->address .
                '<br> City: ' .
                $request->city .
                '<br> Gender: ' .
                $request->gender,
        ];

        MailSendingJob::dispatch($data);

        $hos = new User();
        $hos->firstname = $request->firstname;
        $hos->lastname = $request->lastname;
        $hos->email = $request->email;
        $hos->phone = $request->phone;
        $hos->unique_id = uniqid();
        $hos->address = $request->address;
        $hos->city = $request->city;
        $hos->gender = $request->gender;
        $hos->password = bcrypt($password);

        if (!$hos->save()) {
            helpController::flashSession(false, 'Error saving User');
            return back();
        }
        helpController::flashSession(true, 'User saved successfully');
        return back();
    }

    public function meetings()
    {
        $appointments = Appointment::latest()->get();
        // dd($appointments);
        return view('admins.meeting.index', compact('appointments'));
    }

    public function upcomingMeeting()
    {
        $now = Carbon::now()->toDateString();
        $appointments = Appointment::where('date', '>', $now)->latest()->get();
        return view('admins.meeting.upcoming', compact('appointments'));
    }

    public function passedMeeting()
    {
        $now = Carbon::now()->toDateString();
        $appointments = Appointment::where('date', '<', $now)->latest()->get();
        // dd($appointments);
        return view('admins.meeting.passed', compact('appointments'));
    }

    public function passwordReset()
    {
        return view('admins.passwordreset');
    }
}
