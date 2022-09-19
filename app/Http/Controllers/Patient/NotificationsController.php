<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        return Notification::where('user_id', auth()->user()->id)->get();
    }

    public function update($id)
    {
        $notification = Notification::where('user_id', auth()->user()->id)->where('id',$id)->firstOrFail();
        $notification->read = true;
        $notification->save();

        return $notification;
    }
}
