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
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function index()
    {
        // $hos = Hospital::all()->count();
        // $user = User::all()->count();
        // $doctor = Doctor::all()->count();
        // return view('admin.dashboard', ['hospitals' => $hos, 'user' => $user, 'doctors' => $doctor]);
        return view('admins.home');
    }

    public function users()
    {
        $user = User::all();
        return view('admins.users')->with('user', $user);
    }

    public function userId($id)
    {
        $user = User::findorFail($id);
        return view('admins.userDetails')->with('user', $user);
    }

    public function blockUserId($id)
    {
        $user = User::findorFail($id);
        if($user->delete())
        {
            helpController::flashSession(true, 'User block successfully');
            return back();
        }
    }

    public function doctors()
    {
        $doc = Doctor::get();
        // $hos = Hospital::all();
        // $spec = DB::table('specialties')->get();
        // dd($doc);
        // return view('admin.doctor.index', ['doc' => $doc, 'hos' => $hos, 'spec' => $spec]);
        return view('admins.doctors', ['doc' => $doc]);
    }

    public function verifyHospital()
    {
        $hos = Hospital::all();
        return view('admins.hospital.verify', ['hos' => $hos]);
    }

    public function hospitals()
    {
        $hos = Hospital::all();
        // return view('admin.hospital.index', ['hospitals' => $hos]);
        return view('admins.hospital.index', ['hos' => $hos]);
    }

    public function hospital($id)
    {
        $hos = Hospital::find($id);
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

        $password = rand(111111, 999999);
        $data = [
            'email' => $request->email,
            'subject' => 'Welcome To QuicHealth',
            'name' => 'Welcome',
            'view' => 'mail.mail',
            'content' =>
            'Welcome to QuicHealth, we are happy to have you here. Your hospital profile has been created, below are login details. Login and update your profile <br>
                Name: ' .
                $request->name .
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
                '<br> State: ' .
                $request->state .
                '<br> Country: ' .
                $request->country,
        ];

        MailSendingJob::dispatch($data);

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

    public function updateHospital($id, Request $request)
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

        $hos = Hospital::findOrFail($id);
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

    public function deleteHospital($id)
    {
        $hos = Hospital::findorFail($id);
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

    public function doctor($id)
    {
        $doc = Doctor::find($id);
        $spec = DB::table('specialties')->get();
        $hos = Hospital::all();
        if (!$doc) {
            helpController::flashSession(false, 'Doctor not found');
            return redirect('admin/doctor');
        }
        // return view('admin.doctor.doctor', ['doctor' => $doc, 'specialties' => $spec, 'hospitals' => $hos]);
        return view('admins.doctorDetails', ['doc' => $doc, 'specialties' => $spec, 'hospitals' => $hos]);
    }

    public function addDoctor(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:doctors',
            'hospital' => 'required',
            'phone' => 'required',
            'address' => 'required|string',
            'specialty' => 'required|integer',
        ]);

        $password = rand(111111, 999999);

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
        $data = [
            'email' => $request->email,
            'subject' => 'Welcome Mail',
            'name' => 'Welcome',
            'view' => 'mail.mail',
            'content' =>
            'Welcome to Quichealth, we are happy to have you here. Your doctor profile has been created, below are profile details <br>
                Name: ' .
                $request->name .
                '<br> Email: ' .
                $request->email .
                '<br> Hospital: ' .
                $hos->hospital->name .
                '<br> Password: ' .
                $password .
                '<br> Phone: ' .
                $request->phone .
                '<br> Address: ' .
                $request->address,
        ];
        MailSendingJob::dispatch($data);

        helpController::flashSession(true, 'Doctor saved successfully');
        return back();
    }

    public function updateDoctor(Request $request)
    {
        $this->validate($request, [
            'doctor' => 'required|integer',
            'hospital' => 'required|integer',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'specialty' => 'required|integer',
            'address' => 'required|string',
        ]);

        $hos = Doctor::find($request->doctor);
        if (!$hos) {
            helpController::flashSession(false, 'Doctor not found');
            return redirect('admin/doctors');
        }
        $hos->name = $request->name;
        $hos->email = $request->email;
        $hos->address = $request->address;
        $hos->phone = $request->phone;
        $hos->hospital_id = $request->hospital;

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

        $hos = Doctor::find($request->doctor);
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

    public function admins()
    {
        $admins = Admin::all();
        return view('admins.admins', compact('admins'));
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

    public function passwordReset()
    {
        return view('admins.passwordreset');
    }

    public function hospitalPayout()
    {
        return view('admins.hospitalPayout');
    }

    public function logout()
    {
        auth()->guard('admin')->logout();
        Session::flush();
        return redirect()->route('admin.login');
    }
}
