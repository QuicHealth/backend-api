<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminAuthController extends Controller
{

    public function login()
    {
        return view('admin.login');
    }

    public function authentiate()
    {
        // return view('admin.dashboard');
    }
}