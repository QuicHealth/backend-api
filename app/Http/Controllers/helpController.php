<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class helpController extends Controller
{
    public static function flashSession($status, $msg)
    {
        session()->flash('status', $status);
        session()->flash('msg', $msg);
    }

    public static function getResponse(bool $status, string $message = "", $responseCode, array $data = [])
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $responseCode);
    }
}
