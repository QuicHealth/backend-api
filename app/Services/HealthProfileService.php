<?php

namespace App\Services;

use App\Models\HealthProfile;

class HealthProfileService
{
    protected $profile;

    public function profile()
    {
        // return the health record.
        $this->profile = HealthProfile::where('user_id', auth()->user()->id)->first();

        return $this;
    }


    public function get()
    {
        if ($this->profile) {
            return response()->json([
                'status' => 'success',
                'data' => $this->profile
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

        $updateHealthProfile =  HealthProfile::updateOrCreate(['user_id' => auth()->user()->id], $data);

        if ($updateHealthProfile) {
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