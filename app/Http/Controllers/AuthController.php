<?php

namespace App\Http\Controllers;

use App\Consts\AppVersion;
use App\Consts\CallStatuses;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Throwable;

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

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $settings = Setting::first();
            session(['settings' => $settings]);
            if ($settings->language_id) {
                session(['locale' => $settings->language->code]);
            }
            $userId = auth()->user()->id;
            $roles = User::where('id', $userId)->first()->getRoleNames();
            if (!$roles->contains('Agent')) {
                return redirect()->route('branches', [
                    'branches' => Branch::get()
                ])->with('success', 'Succesfully Logged in!');

            } else {
                $today_queue = Queue::where('created_at', '>=', Carbon::now()->startOfDay())->where('created_at', '<=', Carbon::now())
                    ->where('called', false)->count();
                $today_served = Call::where('created_at', '>=', Carbon::now()->startOfDay())->where('created_at', '<=', Carbon::now())
                    ->where('call_status_id', CallStatuses::SERVED)->count();
                $today_noshow = Call::where('created_at', '>=', Carbon::now()->startOfDay())->where('created_at', '<=', Carbon::now())
                    ->where('call_status_id', CallStatuses::NOSHOW)->count();
                $today_serving = Call::where('created_at', '>=', Carbon::now()->startOfDay())->where('created_at', '<=', Carbon::now())
                    ->whereNull('call_status_id')->count();
                $chart_data = $this->reportRepository->getTodayYesterdayData();
                $reasons = Service::all();
                return  redirect()->route('show_call_page')->with(['reasons' => $reasons, 'counters' => $this->counterRepository->getAllActiveCounters(), 'chart_data' => $chart_data, 'users' => $this->userRepository->getUsers(), 'today_queue' => $today_queue, 'today_noshow' => $today_noshow, 'today_serving' => $today_serving, 'today_served' => $today_served, 'services' => $this->serviceRepository->getAllActiveServices(), 'date' => Carbon::now()->toDateString(), 'show_menu' => true, 'settings' => Setting::first()]);
            }
        }

        return back()->withErrors([
            'error' => 'The provided credentials do not match our records.',
        ]);
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
