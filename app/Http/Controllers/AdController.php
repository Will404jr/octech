<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Branch;
use App\Repositories\AdRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;

// use Spatie\Permission\Models\Role;


class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $ads;

    public function __construct(AdRepository $ads)
    {
        $this->ads = $ads;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ad.index', [
            'ads' => Ad::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ad.create',['branches' => Branch::get()]);
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
                'name' => 'required|unique:ads',
                'branch_id' => 'required',
                'ad_img' => 'required'
            ]);
        DB::beginTransaction();
            $ad = $this->ads->create($request->all());
        } catch (\Exception $e) {
            DB::rollback();
            Log::info('a', [$e]);
            $request->session()->flash('error', $e->getMessage());
            return redirect()->route('ads.index');
        }
        DB::commit();
        $request->session()->flash('success', 'Succesfully inserted the record');
        return redirect()->route('ads.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
        return view('ad.view', [
            'ads' => $this->ads->getAdByBranchId($request->branch_id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Ad $ad)
    {
        return view('ad.edit', [
            'ad' => $ad,
            'branches' => Branch::get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ad $ad)
    {
        try {
            // $request->validate([
            //     'name' => 'required|unique:ads',
            //     'branch_id' => 'required',
            // ]);
        DB::beginTransaction();
            $ad = $this->ads->update($request->all(), $ad);
        } catch (\Exception $e) {
            DB::rollback();
            Log::info('a', [$e]);
            $request->session()->flash('error', 'Something Went Wrong');
            return redirect()->route('ads.index');
        }
        DB::commit();
        $request->session()->flash('success', 'Succesfully updated the record');
        return redirect()->route('ads.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ad $ad, Request $request)
    {

        DB::beginTransaction();
        try {
            $ad = $this->ads->delete($request->all(), $ad);
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', 'Something Went Wrong');
            return redirect()->route('ads.index');
        }
        DB::commit();
        $request->session()->flash('success', 'Succesfully deleted the record');
        return redirect()->route('ads.index');
    }

    public function changeStatus(Request $request)
    {
        try {
            $ad  = Ad::find($request->id);
            DB::beginTransaction();
                $ad->status = !$ad->status;
                $ad->save();
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
