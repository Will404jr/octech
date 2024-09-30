<?php

namespace App\Http\Controllers;

use Adldap\Adldap;
use App\Consts\AppVersion;
use App\Consts\CallStatuses;
use App\Helpers\LDAPAuth;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Call;
use App\Models\Queue;
use App\Models\Service;
use App\Models\Session;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\CallRepository;
use App\Repositories\CounterRepository;
use App\Repositories\ReportRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\TokenRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Throwable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class AuthController extends Controller
{


    protected $counterRepository, $serviceRepository, $tokenRepository, $callRepository, $reportRepository, $userRepository;
    public function __construct(ReportRepository $reportRepository, UserRepository $userRepository, CounterRepository $counterRepository, ServiceRepository $serviceRepository, TokenRepository $tokenRepository, CallRepository $callRepository)
    {
        $this->reportRepository = $reportRepository;
        $this->counterRepository = $counterRepository;
        $this->serviceRepository = $serviceRepository;
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
        $this->callRepository = $callRepository;
    }

    public function home()
    {
        if (auth()->guard('web')->check()) {
            $userId = auth()->user()->id;
            $roles = User::where('id', $userId)->first()->getRoleNames();
            if ($roles->contains('Agent')) {
                return redirect()->route('show_call_page');
            } else {
                return redirect()->route('branches', [
                    'branches' => Branch::get()
                ])->with('success', 'Succesfully Logged in!');
            }
        }

        return redirect()->route('login');
    }

    public function index()
    {
        if (Setting::first() && Setting::first()->installed == 1) $this->removeInstallationFile(Setting::first());

        return view('login.login');
    }
    public function authenticate(Request $request)
    {
        // Check if it's an email or username login attempt
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            // Validate email and password credentials for email login
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
    
            // Attempt to authenticate with the local database
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $this->storeSettingsInSession();
                return $this->redirectBasedOnRoleOrPermission();
            }
    
            // Authentication failed for email login
            return back()->withErrors([
                'error' => 'The provided credentials do not match our records.',
            ]);
        } else {
            // Validate username and password credentials (username stored as 'email' for this validation)
            $credentials = $request->validate([
                'email' => ['required'], // 'email' refers to username in this case
                'password' => ['required'],
            ]);
    
            try {
                // Retrieve the user by username in the local database
                $user = User::where('username', $credentials['email'])->firstOrFail();
    
                // Fetch the corresponding branch
                $branch = Branch::find($user->branch_id);
    
                if (!$branch) {
                    return back()->withErrors([
                        'error' => 'Branch not found for this user.',
                    ]);
                }
    
                // Use the branch's LDAP credentials
                $ldap_host = $branch->db_host; // Corresponds to LDAP host
                $ldap_dn = $branch->db_username; // Corresponds to LDAP distinguished name
                $ldap_password = $branch->db_password; // Corresponds to LDAP password
    
                // Establish LDAP connection
                $ldapConnection = ldap_connect($ldap_host);
                ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
    
                if ($ldapConnection) {
                    // Attempt LDAP bind using branch credentials
                    // Remove the "@" symbol to not suppress errors
                    $ldapBind = ldap_bind($ldapConnection, $ldap_dn, $credentials['password']);
    
                    if ($ldapBind) {
                        // LDAP bind successful, authenticate the user in the app
                        Auth::loginUsingId($user->id);
                        $request->session()->regenerate();
                        $this->storeSettingsInSession();
                        return $this->redirectBasedOnRoleOrPermission();
                    } else {
                        // LDAP bind failed (invalid credentials), fetch error message
                        $ldapError = ldap_error($ldapConnection);
                        return back()->withErrors([
                            'error' => 'Invalid LDAP credentials: ' . $ldapError,
                        ]);
                    }
                } else {
                    // LDAP connection failed
                    return back()->withErrors([
                        'error' => 'Failed to connect to LDAP server for this branch.',
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Login error', ['message' => $e->getMessage()]);
                return back()->withErrors([
                    'error' => 'The provided credentials do not match our records.',
                ]);
            }
        }
    }
    
    
    private function storeSettingsInSession()
    {
        $settings = Setting::first();
        session(['settings' => $settings]);
    
        if ($settings->language_id) {
            session(['locale' => $settings->language->code]);
        }
    }
    
    private function redirectBasedOnRoleOrPermission()
    {
        $user = auth()->user();
    
        if ($user->hasRole('Super-Admin')) {
            return redirect()->route('branches', [
                'branches' => Branch::all()
            ])->with('success', 'Successfully Logged in!');
        }
    
        if ($user->can('view branches')) {
            return redirect()->route('branches.index', [
                'users' => User::with('branch')->where('branch_id', $user->branch_id)->get(),
                'branches' => Branch::all()
            ])->with('success', 'Successfully Logged in!');
        }

        if ($user->can('view queus')) {
            return redirect()->route('queus.index');
        }
    
        if ($user->can('view rates')) {
            return redirect()->route('rates.index');
        }

        if ($user->can('view ads')) {
            return redirect()->route('ads.index');
        }

        if ($user->can('view users')) {
            return redirect()->route('users.index');
        }

        if ($user->can('view roles')) {
            return redirect()->route('roles.index');
        }
    
        if ($user->can('view settings')) {
            return redirect()->route('settings.index');
        }
    
        if ($user->can('view profile')) {
            return redirect()->route('profile');
        }
    
        return redirect()->route('branches.index');
    }
    
        public function logout()
    {
        session()->invalidate();
        Auth::guard('web')->logout();
        return redirect()->route('dashboard');
    }

    public function setEnv()
    {
        file_put_contents(app()->environmentFilePath(), str_replace(
            'SESSION_DRIVER=file',
            'SESSION_DRIVER=database',
            file_get_contents(app()->environmentFilePath())
        ));
    }

    public function filesCurrupted(Request $request)
    {
        return view('vendor.installer.file-currupted', ['app_version' => AppVersion::VERSION]);
    }

    public function removeInstallationFile(Setting $settings)
    {
        File::delete(base_path('/app/Http/Controllers/InstallerController.php'));
        File::delete(base_path('/app/Repositories/InstallerRepository.php'));
        File::delete(base_path('/config/installer.php'));
        $data = '<?php
        ';
        file_put_contents(base_path('/routes/install.php'), $data);
        $settings->installed = 2;
        $settings->save();
        try {
            Artisan::call('optimize');
            Artisan::call('route:clear');
        } catch (Throwable $th) {
        }

        return $settings;
    }
}
