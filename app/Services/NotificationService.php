<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    protected $user_type;

    protected $authUserId;

    protected $notification;

    protected $allNotifications;

    public function __construct($user_type, $authUserId)

    {
        $this->user_type = $user_type;
        $this->authUserId = $authUserId;
    }

    public function notifications()
    {
        $this->allNotifications = Notification::where('user_type', $this->user_type)
            ->where('user_id', $this->authUserId)
            ->get();

        return $this;
    }

    public function notification($id)
    {
        $this->notification = Notification::where('user_type', $this->user_type)
            ->where('user_id', $this->authUserId)
            ->where('id', $id)
            ->first();

        return $this;
    }


    public function all()
    {

        if ($this->allNotifications) {

            return response()->json([
                'status' => true,
                'message' => 'notification found',
                'data' => [
                    "notifications"  =>  $this->allNotifications,
                    "num_of_notification" => count($this->allNotifications)
                ]
            ], 200);
        } else {

            return response()->json([
                'status' => false,
                'message' => 'no notification found'
            ], 401);
        }
    }

    public function update()
    {
        if ($this->notification == null) {

            return response()->json([
                'status' => false,
                'message' => 'no notification found'
            ], 401);
        } else {

            $this->notification->read_reciept = true;

            if ($this->notification->save()) {

                return response()->json([
                    'status' => true,
                    'message' => $this->notification
                ], 200);
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Error'
                ], 401);
            }
        }
    }
}