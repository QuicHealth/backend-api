<?php

namespace App\Http\Controllers\Hospital;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Settings::where('hospital_id', auth('hospital_api')->user()->id)->first();

        return response([
            'status' => true,
            'msg' => 'Settings retrieved successfully',
            'data' => $settings
        ]);
    }

    public function settings(Request $request)
    {
        $this->validate($request, [
            'bank' => 'required|string',
            'acc_name' => 'required|string',
            'acc_no' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        $settings = Settings::updateOrCreate(
            ['hospital_id' => auth('hospital_api')->user()->id],
            [
                'bank' => $request['bank'],
                'acc_name' => $request['acc_name'],
                'acc_no' =>  $request['acc_no'],
                'amount' =>  $request['amount']
            ]
        );


        return response([
            'status' => true,
            'msg' => 'Settings saved successfully',
            'data' => $settings
        ]);

    }
}
