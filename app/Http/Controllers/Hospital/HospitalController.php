<?php

namespace App\Http\Controllers\Hospital;

use App\Hospital;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HospitalController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    public function UpdateProfile(Request $request, $id)
    {
        $hospital = Hospital::find($id);

        if (!$hospital) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Hospital with id ' . $id . ' cannot be found',
            ], 400);
        }

        $updated = $hospital->fill($request->all())->save();

        if ($updated) {
            return response()->json([
                'success' => true,
                'hospital' => $hospital,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product could not be updated',
            ], 500);
        }

    }
}
