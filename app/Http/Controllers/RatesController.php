<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rate;
use App\Models\Service;
use App\Models\Setting;
use App\Repositories\RateRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

// use Spatie\Permission\Models\Role;


class RatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $rates;

    public function __construct(RateRepository $rates)
    {
        $this->rates = $rates;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('rate.index', [
            'rates' => $this->rates->getAllRates()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = json_decode(file_get_contents(resource_path('views/details/country.json')));
        return view('rate.create', ['settings' => Setting::first(), 'countries' => $countries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            $request->validate([
                'country_name_text' => 'required',
                'country_code' => 'required',
                'country_flag_text' => 'required',
                'currency_code' => 'required',
                'buying_rate' => 'required|numeric',
                'selling_rate' => 'required|numeric',
            ]);
            DB::beginTransaction();
            $rate = $this->rates->create($request->all());
        } catch (\Exception $e) {

            DB::rollback();
            $request->session()->flash('error', 'Something Went Wrong');
            return redirect()->route('rates.index');
        }
        DB::commit();
        $request->session()->flash('success', 'Succesfully inserted the record');
        return redirect()->route('rates.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('rate.view', [
            'rates' => $this->rates->getAllActiveRates()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Rate $rate)
    {
        $countries = json_decode(file_get_contents(resource_path('views/details/country.json')));
        return view('rate.edit', [
            'rate' => $rate
            , 'countries' => $countries,
            'settings' => Setting::first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rate $rate)
    {
        try {
            // $request->validate([
            //     'country_name_text' => 'required',
            //     'country_code' => 'required',
            //     'country_flag_text' => 'required',
            //     'currency_code' => 'required',
            //     'buying_rate' => 'required|numeric',
            //     'selling_rate' => 'required|numeric',
            // ]);
            DB::beginTransaction();
                $rate = $this->rates->update($request->all(), $rate);
        } catch (\Exception $e) {
            DB::rollback();
            Log::info('a', [$e]);
            $request->session()->flash('error', 'Something Went Wrong');
            return redirect()->route('rates.index');
        }
        DB::commit();
        $request->session()->flash('success', 'Succesfully updated the record');
        return redirect()->route('rates.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rate $rate, Request $request)
    {

        DB::beginTransaction();
        try {
            $rate = $this->rates->delete($request->all(), $rate);
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', 'Something Went Wrong');
            return redirect()->route('rates.index');
        }
        DB::commit();
        $request->session()->flash('success', 'Succesfully deleted the record');
        return redirect()->route('rates.index');
    }

    public function get_details_by_country_code(Request $request)
    {
        $countryObject = null;
        $countries = json_decode(file_get_contents(resource_path('views/details/country.json')));
        foreach ($countries as $item) {
            if ($item->code == $request->country_code) {
                $countryObject = $item;
            }
        }
        echo json_encode($countryObject);

    }

    public function changeStatus(Request $request)
    {
        $rate  = Service::find($request->id);

        DB::beginTransaction();
        try {
            $rate->status = !$rate->status;
            $rate->save();
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', 'Something Went Wrong');
            return 'Something went wrong';
        }
        DB::commit();
        $request->session()->flash('success', 'Succesfully updated the record');
        return 'Success';
    }

}
