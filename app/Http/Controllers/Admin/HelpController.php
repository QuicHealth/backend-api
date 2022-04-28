<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public static function flashSession($status, $msg){
        session()->flash('status', $status);
        session()->flash('msg', $msg);
    }
}
