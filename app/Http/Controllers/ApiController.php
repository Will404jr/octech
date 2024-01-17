<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Bills;
use App\Models\PivotUsers;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth.basic');
    // }

    public function getDisplayAds(Request $request)
    {
        try {
            Log::info('Display Ads Request', [$request]);
            $ads = Ad::where('status', 1)->get();
            return response(
                [
                    'success' => true,
                    'ads' => $ads,
                ]
            );
        } catch (Exception $e) {
            Log::info('Display Ads Exception Error', [$e->getMessage()]);
            return response(['success' => false, 'message' => 'Failure to Display Ads, connection error please try again.']);
        }
    }

    public function getExchangeRates(Request $request)
    {
        try {
            Log::info('Exchange Rates Request', [$request]);
            $rates = Rate::where('status', 1)->get();
            return response(
                [
                    'success' => true,
                    'rates' => $rates,
                ]
            );
        } catch (Exception $e) {
            Log::info('Exchange Rates Exception Error', [$e->getMessage()]);
            return response(['success' => false, 'message' => 'Failure to Exchange Rates, connection error please try again.']);
        }
    }
}
