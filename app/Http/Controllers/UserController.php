<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

// use Spatie\Permission\Models\Role;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.index', [
            'users' => User::with('branch')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create', ['roles' => Role::get(), 'branches' => Branch::get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('a',[$request->all()]);
        $request->validate([
            'email' => 'required|email|unique:users',
            'last_name' => 'required',
            'user_name' => 'required',
            'branch_id' => 'required',
            'first_name' => 'required',
            'password' => 'required|min:6',
            'role' => 'exists:roles,id'
        ]);
        DB::beginTransaction();
        try {
            $users = $this->users->create($request->all());
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', 'Something Went Wrong');
            return redirect()->route('users.index');
        }
        DB::commit();
        $request->session()->flash('success', 'Succesfully inserted the record');
        return redirect()->route('users.index');
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
    public function edit(User $user)
    {
        return view('user.edit', [
            'user' => $user,
            'branches' => Branch::get(),
            'roles' => Role::get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            // 'email' => 'required|email|unique:users,email,' . $user->id,
            'first_name' => 'required',
            'last_name' => 'required',
            'role' => 'required|exists:roles,id'
        ]);
        DB::beginTransaction();
        try {
            $users = $this->users->update($request->all(), $user);
        } catch (\Exception $e) {
            Log::info('a',[$e->getMessage()]);
            DB::rollback();
            $request->session()->flash('error', 'Something Went Wrong');
            return redirect()->route('users.index');
        }
        DB::commit();
        $request->session()->flash('success', 'Succesfully updated the record');
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Request $request)
    {
        $cuser = Auth::user();
        if ($user->id == $cuser->id) {
            $request->session()->flash('warning', 'Cannot delete current user');
            return redirect()->route('users.index');
        } else {
            DB::beginTransaction();
            try {
                $user = $this->users->delete($request->all(), $user);
            } catch (\Exception $e) {
                DB::rollback();
                $request->session()->flash('error', 'Something Went Wrong');
                return redirect()->route('users.index');
            }
            DB::commit();
            $request->session()->flash('success', 'Succesfully deleted the record');
            return redirect()->route('users.index');
        }
    }
}
