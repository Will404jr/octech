<?php

namespace App\Http\Controllers;

use App\Consts\CallStatuses;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Call;
use App\Models\Queue;
use App\Models\Reason;
use App\Models\Service;
use App\Models\Setting;
use App\Repositories\QueueRepository;
use App\Repositories\ReasonRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

// use Spatie\Permission\Models\Role;


class QueueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $queues;

    public function __construct(QueueRepository $queues)
    {
        $this->queues = $queues;
    }
    public function index()
    {
        $branchId = auth()->user()->branch_id;
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
        return view('queue.index', [
            'queues' => $this->queues->getAllActiveQueues()
        ]);
    }

    public function served(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $ticket = Queue::where('id', $request->id)->where('status', 'Assigned')->first();
            if ($ticket) {
                $dbConnection = Branch::where('id', $ticket->branch)->first();
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
                $ticket = $this->queues->servedTicket($ticket);
            } else {
                return response()->json(['already_executed' => true]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('a', [$e->getMessage()]);
            return response()->json(['status_code' => 500]);
        }
        DB::commit();
        return response()->json($ticket);
    }

    public function noShowToken(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $ticket = Queue::where('id', $request->id)->where('status', 'Assigned')->first();
            if ($ticket) {
                $dbConnection = Branch::where('id', $ticket->branch)->first();
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
                $ticket = $this->queues->noShowTicket($ticket);
            } else {
                return response()->json(['already_executed' => true]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status_code' => 500]);
        }
        db::commit();
        return response()->json($ticket);
    }

    public function recallToken(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:calls,id',
        ]);
        DB::beginTransaction();
        try {
            $ticket = Queue::where('id', $request->id)->whereIn('status', ['Assigned', 'noshow'])->first();
            if ($ticket) {
                $dbConnection = Branch::where('id', $ticket->branch)->first();
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
                $ticket = $this->queues->noShowTicket($ticket);
            } else {
                return response()->json(['already_executed' => true]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status_code' => 500]);
        }

        DB::commit();
        return response()->json($ticket);
    }


    public function serve($id)
    {
        $username = auth()->user()->username;
        $ticket = Queue::where('id', $id)->whereNull('status')->orWhere('status', '!=', 'completed')->first();
        $dbConnection = Branch::where('id', auth()->user()->branch_id)->first();
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
        $today_queue = Queue::where('status', NULL)->count();
        $today_served = Queue::where('status', 'completed')->where('agent_assigned', $username)->count();
        if($ticket != null)
        {
            Queue::where('id', $id)->update(['agent_assigned' => $username, 'status' => 'Assigned']);
        }
        return view('call.call', ['ticket' => $ticket, 'today_queue' => $today_queue, 'today_served' => $today_served, 'date' => Carbon::now()->toDateString(), 'show_menu' => true, 'settings' => Setting::first()]);
    }
}
