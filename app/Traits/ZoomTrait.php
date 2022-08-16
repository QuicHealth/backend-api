<?php

namespace App\Traits;

use App\Facades\Zoom;

/**
 * trait ZoomTrait
 */
trait ZoomTrait
{

    public function getAuthUrl()
    {
        return Zoom::oAuthUrl();
    }

    public function getToken($code)
    {
        return Zoom::token($code);
    }

    public function refreshToken()
    {
        return Zoom::refreshToken();
    }

    public function getAllMeeting(array $data)
    {
        return Zoom::listMeeting($data);
    }

    public function createMeeting(array $data)
    {

        return Zoom::createMeeting($data);
    }

    public function deleteMeeting($meetingId, array $data)
    {
        return Zoom::deleteMeeting($meetingId, $data);
    }

    protected function rand_string($length)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }
}