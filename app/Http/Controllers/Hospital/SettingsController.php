<?php

namespace App\Http\Controllers\Hospital;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'bank' => 'required|string',
            'acc_name' => 'required|string',
            'acc_no' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        $Settings = Settings::updateOrCreate(
            [
                'hospital_id' => auth('hospital_api')->user()->id,
                'bank' => $request['bank'],
                'acc_name' => $request['acc_name'],
                'acc_no' =>  $request['acc_no'],
                'price' =>  $request['price']
            ]
        );


        return response([
            'status' => true,
            'msg' => 'Settings saved successfully',
            'data' => $Settings
        ]);
    }
}