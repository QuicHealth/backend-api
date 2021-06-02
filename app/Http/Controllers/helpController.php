<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class helpController extends Controller
{
    public static function flashSession($status, $msg){
        session()->flash('status', $status);
        session()->flash('msg', $msg);
    }
}
