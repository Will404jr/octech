<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\User;
use App\Repositories\AgentRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
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
    public $agents;

    public function __construct(UserRepository $users, AgentRepository $agents)
    {
        $this->users = $users;
        $this->agents = $agents;
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
        $request->validate([
            'email' => 'required|email|unique:users',
            'last_name' => 'required',
            'user_name' => 'required',
            'branch_id' => 'required',
            'first_name' => 'required',
            'role' => 'exists:roles,id'
        ]);
        DB::beginTransaction();
        try {
            $users = $this->users->create($request->all());
            $dbConnection = Branch::where('id', $request->branch_id)->first();
            $dbHost = $dbConnection->db_host;
            $dbName = $dbConnection->db_name;
            $dbUsername = $dbConnection->db_username;
            $dbPassword = $dbConnection->db_password;
            DB::purge('mysqlqms');
            Config::set('database.connections.mysqlqms', [
                'driver' => 'mysql',
                'host' => $dbHost,
                'database' => $dbName,
                'username' => $dbUsername,
                'password' => $dbPassword
            ]);
            $agents = $this->agents->create($request->all());
        } catch (\Exception $e) {
            DB::rollback();
            Log::info('User Creation', [$e->getMessage()]);
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
            $agentId = $user->username;
            $users = $this->users->update($request->all(), $user);
            $dbConnection = Branch::where('id', $user->branch_id)->first();
            $dbHost = $dbConnection->db_host;
            $dbName = $dbConnection->db_name;
            $dbUsername = $dbConnection->db_username;
            $dbPassword = $dbConnection->db_password;
            DB::purge('mysqlqms');
            Config::set('database.connections.mysqlqms', [
                'driver' => 'mysql',
                'host' => $dbHost,
                'database' => $dbName,
                'username' => $dbUsername,
                'password' => $dbPassword
            ]);
            $agent = Agent::where('id', $agentId)->first();
            $agents = $this->agents->update($request->all(), $agent);
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
                $branchId = $user->branch_id;
                $agentId = $user->username;
                $user = $this->users->delete($request->all(), $user);
                $dbConnection = Branch::where('id', $branchId)->first();
                $dbHost = $dbConnection->db_host;
                $dbName = $dbConnection->db_name;
                $dbUsername = $dbConnection->db_username;
                $dbPassword = $dbConnection->db_password;
                DB::purge('mysqlqms');
                Config::set('database.connections.mysqlqms', [
                    'driver' => 'mysql',
                    'host' => $dbHost,
                    'database' => $dbName,
                    'username' => $dbUsername,
                    'password' => $dbPassword
                ]);
                $agent = Agent::where('id', $agentId)->first();
                $agent = $this->agents->delete($request->all(), $agent);
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
