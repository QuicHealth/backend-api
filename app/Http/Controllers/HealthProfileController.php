<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthProfile;

class HealthProfileController extends Controller
{
    //
    public function healthProfile()
    {
        // return the health record.
        $healthProfile = HealthProfile::where('id', auth()->user()->id)->first();

        return response()->json([
            'status' => true,
            'message' => 'Health Profile',
            'data' => $healthProfile
        ], 200);
    }

    public function saveHealthProfile(Request $request)
    {
        // save healthProfile

        // $this->validate($request, [
        //     'blood_group'     => 'sometimes',
        //     'genotype'      => 'sometimes',
        //     'martial_status'        => 'sometimes',
        //     'medication'           => 'sometimes',
        //     'family_medical_history'  => 'sometimes',
        //     'health_condition'  => 'sometimes',
        //     'peculiar_cases'  => 'sometimes',
        //     'allergies'  => 'sometimes',
        //     'Occupation'  => 'sometimes',
        //     'past_medical_history'  => 'sometimes',
        // ]);

        $healthProfile = HealthProfile::where('id', auth()->user()->id)->first();

        $request->has('blood_group') ? $healthProfile->blood_group = $request->blood_group : false;
        $request->has('genotype') ? $healthProfile->genotype = $request->genotype : false;
        $request->has('martial_status') ? $healthProfile->martial_status = $request->martial_status : false;
        $request->has('medication') ? $healthProfile->medication = $request->medication : false;
        $request->has('family_medical_history') ? $healthProfile->family_medical_history = $request->family_medical_history : false;
        $request->has('health_condition') ? $healthProfile->health_condition = $request->health_condition : false;
        $request->has('peculiar_cases') ? $healthProfile->peculiar_cases = $request->peculiar_cases : false;
        $request->has('allergies') ? $healthProfile->allergies = $request->allergies : false;
        $request->has('Occupation') ? $healthProfile->Occupation = $request->Occupation : false;
        $request->has('past_medical_history') ? $healthProfile->past_medical_history = $request->past_medical_history : false;

        if (!$healthProfile->save()) {
            return response([
                'status' => false,
                'message' => 'Error updating profile, pls try again',
            ], 400);
        }

        return response([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => $healthProfile,
        ]);
    }
}