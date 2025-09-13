<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Show profile settings
     */
    public function profile()
    {
        return view('settings.profile');
    }

    /**
     * Show password settings
     */
    public function password()
    {
        return view('settings.password');
    }

    /**
     * Show appearance settings
     */
    public function appearance()
    {
        return view('settings.appearance');
    }
}

