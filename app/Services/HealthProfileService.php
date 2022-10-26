<?php

namespace App\Services;

use App\Models\HealthProfile;

class HealthProfileService
{
    protected $healthProfile;

    public function profile()
    {
        // return the health record.
        $this->healthProfile = HealthProfile::where('user_id', auth()->user()->id)->first();

        return $this;
    }


    public function get()
    {
        if ($this->healthProfile) {
            return response()->json([
                'status' => 'success',
                'data' => $this->healthProfile
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No health record found'
            ]);
        }
    }


    public function update($data)
    {

        $data['blood_group'] ? $this->healthProfile->blood_group = $data['blood_group'] : '';
        $data['genotype'] ? $this->healthProfile->genotype = $data['genotype'] : '';
        $data['martial_status'] ? $this->healthProfile->martial_status = $data['martial_status'] : '';
        $data['medication'] ? $this->healthProfile->medication = $data['medication'] : '';
        $data['family_medical_history'] ? $this->healthProfile->family_medical_history = $data['family_medical_history'] : '';
        $data['health_condition'] ? $this->healthProfile->health_condition = $data['health_condition'] : '';
        $data['peculiar_cases'] ? $this->healthProfile->peculiar_cases = $data['peculiar_cases'] : '';
        $data['allergies'] ? $this->healthProfile->allergies = $data['allergies'] : '';
        $data['Occupation'] ? $this->healthProfile->Occupation = $data['Occupation'] : '';
        $data['past_medical_history'] ? $this->healthProfile->past_medical_history = $data['past_medical_history'] : '';

        if ($this->healthProfile->save()) {
            return response([
                'status' => 'success',
                'message' => 'Health Profile updated successfully',
            ], 200);
        }

        return response([
            'status' => 'error',
            'message' => 'Error updating profile, pls try again',
        ], 400);
    }
}