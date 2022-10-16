<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        $notification = Notification::where('user_type', 'Patient')
            ->where('user_id', auth()->user()->id)->get();
        if ($notification) {
            return response()->json([
                'status' => true,
                'message' => $notification
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 401);
        }
    }

    public function update($id)
    {
        $notification = Notification::where('user_type', 'Patient')
            ->where('user_id', auth()->user()->id)->where('id', $id)->firstOrFail();
        $notification->userRead = true;

        if ($notification->save()) {
            return response()->json([
                'status' => true,
                'message' => $notification
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 401);
        }
    }
}