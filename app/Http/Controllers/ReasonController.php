<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Reason;
use App\Repositories\ReasonRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

// use Spatie\Permission\Models\Role;


class ReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $reasons;

    public function __construct(ReasonRepository $reasons)
    {
        $this->reasons = $reasons;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reason.index', [
            'reasons' => Reason::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('reason.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:reasons',
            'description' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $reasons = $this->reasons->create($request->all());
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', 'Something Went Wrong');
            return redirect()->route('reasons.index');
        }
        DB::commit();
        $request->session()->flash('success', 'Succesfully inserted the record');
        return redirect()->route('reasons.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $reason)
    {
        return view('reason.edit', [
            'reason' => $reason,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Branch $reason)
    {
        $request->validate([
            'code' => 'required', Rule::unique('reasons', 'code')->ignore($reason->id),
            'description' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $reasons = $this->reasons->update($request->all(), $reason);
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', 'Something Went Wrong');
            return redirect()->route('reasons.index');
        }
        DB::commit();
        $request->session()->flash('success', 'Succesfully updated the record');
        return redirect()->route('reasons.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reason $reason, Request $request)
    {
        DB::beginTransaction();
        try {
            $reasons = $this->reasons->delete($request->all(), $reason);
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', 'Something Went Wrong');
            return redirect()->route('reasons.index');
        }
        DB::commit();
        $request->session()->flash('success', 'Succesfully deleted the record');
        return redirect()->route('reasons.index');
    }
}
