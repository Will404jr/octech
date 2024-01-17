<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Rate;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DisplayController extends Controller
{
    public function showDisplayUrl()
    {
        $rates =  Rate::where('status', 1)->get();
        $ads = Ad::where('status', 1)->get();
        return view('display.index', ['rates' => $rates, 'ads' => $ads, 'date' => Carbon::now()->toDateString(), 'settings' => Setting::first(),'file'=>'storage/app/public/tokens_for_display.json']);
    }
}
