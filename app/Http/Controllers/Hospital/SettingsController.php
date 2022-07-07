<?php

namespace App\Http\Controllers\Hospital;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        // dd(auth('hospital_api')->user()->id);
        $this->validate($request, [
            // 'hospital_id' => 'required',
            'bank' => 'required|string',
            'acc_name' => 'required|string',
            'acc_no' => 'required',
            'price' => 'required',
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
