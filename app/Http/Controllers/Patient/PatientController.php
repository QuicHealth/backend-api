<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Patient\BaseController;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Patient::all();

        return $this->sendResponse($patients->toArray(), 'The patients was retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $input = $request->all();

        // $validator = Validator::make($input, [
        //     'fullname' => 'required',
        //     'email' => 'required',
        //     'phone_number' => 'required'
        // ]);

        // if($validator->fails()){
        //     return $this->sendError('Validation Error.', $validator->errors());
        // }

        // $patient = Patient::create($input);

        // return $this->sendResponse($patient->toArray(), 'A new Patient has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        $patient = Patient::find($patient->id);

        if (is_null($patient)) {
            return $this->sendError('Patient not found.');
        }

        return $this->sendResponse($patient->toArray(), 'Patient retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'fullname' => 'required',
            'email' => 'required',
            'phone_number' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $patient->name = $input['name'];
        $patient->detail = $input['detail'];
        $patient->save();

        return $this->sendResponse($patient->toArray(), 'updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();

        return $this->sendResponse($patient->toArray(), 'Deleted successfully.');
    }
}
