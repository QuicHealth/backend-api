<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Hospital;
use App\User;
use App\Doctor;
use App\Http\Controllers\helpController;

class AdminController extends Controller
{
    public function index(){
        $hos = Hospital::all()->count();
        $user = User::all()->count();
        return view('admin.dashboard', ['hospital'=>$hos, 'user'=>$user]);
    }   

    public function hospitals(){
        $hos = Hospital::all();
        return view('admin.hospitals', ['hospitals'=>$hos]);
    }

    public function hospital($id){
        $hos = Hospital::find($id);
        if(!$hos){
            helpController::flashSession(false, 'Hospital not found');
            return redirect('admin/hospitals');
        }
        return view('admin.hospital', ['hospital'=>$hos]);
    }

    public function addHospital(Request $request){
        $this->validate($request, [
            'name'=>'required|string|unique:hospitals',
            'email'=>'required|email',
            'address'=>'required|string',
            'city'=>'required|string',
            'state'=>'required|string',
            'country'=>'required|string',
        ]);

        $hos = new Hospital();
        $hos->name = $request->name;
        $hos->email = $request->email;
        $hos->address = $request->address;
        $hos->city = $request->city;
        $hos->state = $request->state;
        $hos->country = $request->country;

        if(!$hos->save()){
            helpController::flashSession(false, 'Error saving hospital');
            return back();
        }
        helpController::flashSession(true, 'Hospital saved successfully');
        return back();
    }

    public function updateHospital(Request $request){
        $this->validate($request, [
            'hospital'=>'required|integer',
            'name'=>'required|string',
            'email'=>'required|email',
            'address'=>'required|string',
            'city'=>'required|string',
            'state'=>'required|string',
            'country'=>'required|string',
        ]);

        $hos = Hospital::find($request->hospital);
        if(!$hos){
            helpController::flashSession(false, 'Hospital not found');
            return redirect('admin/hospitals');
        }
        $hos->name = $request->name;
        $hos->email = $request->email;
        $hos->address = $request->address;
        $hos->city = $request->city;
        $hos->state = $request->state;
        $hos->country = $request->country;

        if(!$hos->save()){
            helpController::flashSession(false, 'Error updating hospital');
            return back();
        }
        helpController::flashSession(true, 'Hospital updated successfully');
        return back();
    }

    public function deleteHospital(Request $request){
        $this->validate($request, [
            'hospital'=>'required|integer'
        ]);

        $hos = Hospital::find($request->hospital);
        if(!$hos){
            helpController::flashSession(false, 'Hospital not found');
            return redirect('admin/hospitals');
        }

        if(!$hos->delete()){
            helpController::flashSession(false, 'Error deleting hospital');
            return back();
        }
        helpController::flashSession(false, 'Hospital deleted successfully');
        return redirect('admin/hospitals');
    }

    public function doctors(){
        $doc = Doctor::all();
        $hos = Hospital::all();
        return view('admin.doctors', ['doctors'=>$doc, 'hospitals'=>$hos]);
    }

    public function doctor($id){
        $doc = Doctor::find($id);
        if(!$doc){
            helpController::flashSession(false, 'Doctor not found');
            return redirect('admin/doctors');
        }
        return view('admin.doctor', ['doctor'=>$doc]);
    }

    public function addDoctor(Request $request){
        $this->validate($request, [
            'name'=>'required|string',
            'email'=>'required|email',
            'hospital'=>'required',
            'address'=>'required|string',
            'city'=>'required|string',
        ]);

        $hos = new Doctor();
        $hos->name = $request->name;
        $hos->email = $request->email;
        $hos->ho = $request->address;
        $hos->city = $request->city;
        $hos->hospital_id = $request->hospital;

        if(!$hos->save()){
            helpController::flashSession(false, 'Error saving doctor');
            return back();
        }
        helpController::flashSession(true, 'Doctor saved successfully');
        return back();
    }

    public function updateDoctor(Request $request){
        $this->validate($request, [
            'doctor'=>'required|integer',
            'hospital'=>'required|integer',
            'name'=>'required|string',
            'email'=>'required|email',
            'address'=>'required|string',
            'city'=>'required|string',
        ]);

        $hos = Doctor::find($request->doctor);
        if(!$hos){
            helpController::flashSession(false, 'Doctor not found');
            return redirect('admin/doctors');
        }
        $hos->name = $request->name;
        $hos->email = $request->email;
        $hos->address = $request->address;
        $hos->city = $request->city;
        $hos->hospital_id = $request->hospital;

        if(!$hos->save()){
            helpController::flashSession(false, 'Error updating doctor');
            return back();
        }
        helpController::flashSession(true, 'Doctor updated successfully');
        return back();
    }

    public function deleteDoctor(Request $request){
        $this->validate($request, [
            'doctor'=>'required|integer'
        ]);

        $hos = Doctor::find($request->doctor);
        if(!$hos){
            helpController::flashSession(false, 'Doctor not found');
            return redirect('admin/doctors');
        }

        if(!$hos->delete()){
            helpController::flashSession(false, 'Error deleting doctor');
            return back();
        }
        helpController::flashSession(false, 'Doctor deleted successfully');
        return redirect('admin/doctors');
    }


}
