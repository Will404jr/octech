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
        if (auth()->user()->roles[0]->name == 'Super-Admin') {
            return view('user.index', [
                'users' => User::with('branch')->get(),
                'branches' => Branch::get()
            ]);
        } else {
            return view('user.index', [
                'users' => User::with('branch')->where('branch_id', auth()->user()->branch_id)->get(),
                'branches' => Branch::get()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->roles[0]->name == 'Super-Admin') {
            return view('user.create', ['roles' => Role::get(), 'branches' => Branch::get()]);
        } else {
            return view('user.create', ['roles' => Role::get(), 'branches' => Branch::where('id', auth()->user()->branch_id)->get()]);
        }
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
            // Create the user in the default database
            $users = $this->users->create($request->all());
    
            // Retrieve branch credentials
            $dbConnection = Branch::where('id', $request->branch_id)->first();
            $dbHost = $dbConnection->db_host;
            $dbName = $dbConnection->db_name;
            $dbUsername = $dbConnection->db_username;
            $dbPassword = $dbConnection->db_password;
    
            // Set up dynamic database connection for 'mysqlqms'
            DB::purge('mysqlqms');
            Config::set('database.connections.mysqlqms', [
                'driver' => 'mysql',
                'host' => $dbHost,
                'database' => $dbName,
                'username' => $dbUsername,
                'password' => $dbPassword,
            ]);
    
            // Test the database connection and log success or failure
            try {
                $connectedDatabase = DB::connection('mysqlqms')->getDatabaseName();
                Log::info('Connected to the database successfully: ' . $connectedDatabase);
            } catch (\Exception $e) {
                Log::error('Failed to connect to database: ' . $e->getMessage());
                throw new \Exception('Database connection error');
            }
    
            // LDAP connection test (only logging success/failure)
          // LDAP connection test (only logging success/failure)
try {
    // Connect to the LDAP server using secure LDAPS
    $ldapConnection = ldap_connect('ldaps://ldap.forumsys.com', 636); // Use SSL for secure connection
    ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
    
    if ($ldapConnection) {
        Log::info('Successfully connected to the LDAP server.');
        
        // Authenticate with LDAP
        $ldapBind = ldap_bind($ldapConnection, 'cn=read-only-admin,dc=example,dc=com', 'password');
        
        if ($ldapBind) {
            Log::info('LDAP bind successful.');
        } else {
            Log::error('LDAP bind failed.');
        }
    } else {
        Log::error('Failed to connect to LDAP server.');
    }
} catch (\Exception $e) {
    Log::error('LDAP connection error: ' . $e->getMessage());
}

    
        } catch (\Exception $e) {
            DB::rollback();
            Log::info('User Creation', [$e->getMessage()]);
            $request->session()->flash('error', 'Something Went Wrong');
            return redirect()->route('users.index');
        }
    
        DB::commit();
        $request->session()->flash('success', 'Successfully inserted the record');
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
        if (auth()->user()->roles[0]->name == 'Super-Admin') {
            return view('user.edit', [
                'user' => $user,
                'branches' => Branch::get(),
                'roles' => Role::get()
            ]);
        } else {
            return view('user.edit', [
                'user' => $user,
                'branches' => Branch::where('id', auth()->user()->branch_id)->get(),
                'roles' => Role::get()
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */public function update(Request $request, User $user)
{
    $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'role' => 'required|exists:roles,id'
    ]);

    DB::beginTransaction();
    try {
        // Only update in your primary database
        $users = $this->users->update($request->all(), $user);
        
        // Skip any LDAP modifications, only modify in primary database

    } catch (\Exception $e) {
        Log::info('Error updating user', [$e->getMessage()]);
        DB::rollback();
        $request->session()->flash('error', 'Something Went Wrong');
        return redirect()->route('users.index');
    }

    DB::commit();
    $request->session()->flash('success', 'Successfully updated the record');
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
                // Only delete user from your database
                $user = $this->users->delete($request->all(), $user);
                
                // Skip LDAP user deletion
    
            } catch (\Exception $e) {
                DB::rollback();
                $request->session()->flash('error', 'Something Went Wrong');
                return redirect()->route('users.index');
            }
            DB::commit();
            $request->session()->flash('success', 'Successfully deleted the record');
            return redirect()->route('users.index');
        }
    }
    

//     public function testConnection()
// {
//     try {
//         DB::purge('mysqlqms');  // Ensure the connection is refreshed
//         $db = DB::connection('mysqlqms')->getPdo();
//         $status = $db->getAttribute(\PDO::ATTR_CONNECTION_STATUS);

//         return response()->json([
//             'message' => 'Connected to the database successfully',
//             'status' => $status
//         ]);
//     } catch (\Exception $e) {
//         return response()->json([
//             'error' => 'Connection failed: ' . $e->getMessage()
//         ], 500);
//     }
// }

 }


