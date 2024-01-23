<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class MarqueeController extends Controller
{

    public function show()
    {
        //
        return view('marquee.view', [
            'settings' => Setting::first()
        ]);
    }
}
