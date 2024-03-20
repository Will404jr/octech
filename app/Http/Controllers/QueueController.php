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
            $ticket = Queue::where('id', $request->id)->where('status', 'Assigned')->first();
            if ($ticket) {
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
            $ticket = Queue::where('id', $request->id)->where('status', 'Assigned')->first();
            if ($ticket) {
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
            $ticket = Queue::with('reason')->where('id', $request->id)->whereIn('status', ['Assigned', 'noshow'])->first();
            if ($ticket) {
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
        $ticket = Queue::with('reason')->where('id', $id)->whereNull('status')->first();
        $today_queue = Queue::where('status', NULL)->count();
        $today_served = Queue::where('status', 'completed')->where('agent_assigned', $username)->count();
        if ($ticket != null) {
            Queue::where('id', $id)->update(['service_start' => Carbon::now(), 'agent_assigned' => $username, 'status' => 'Assigned']);
        }
        return view('call.call', ['ticket' => $ticket, 'today_queue' => $today_queue, 'today_served' => $today_served, 'date' => Carbon::now()->toDateString(), 'show_menu' => true, 'settings' => Setting::first()]);
    }

    public function getQueueData()
    {
        $username = auth()->user()->username;
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
        return response()->json(['today_queue' => $today_queue,'today_served' => $today_served,]);
    }

    public function callNext()
    {
        DB::beginTransaction();
        try {
            $username = auth()->user()->username;
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
            $ticket = Queue::with('reason')->whereNull('status')->first();
            if ($ticket != null) {
                Queue::where('id', $ticket->id)->update(['service_start' => Carbon::now(), 'agent_assigned' => $username, 'status' => 'Assigned']);
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

    public function editTokenDetails(Request $request)
    {
        DB::beginTransaction();
        try {
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
            $ticket = Queue::with('reason')->where('id', $request->id)->first();
            $reason = isset($request->reason) ? $request->reason : $ticket->reason->reason;
            $phone = isset($request->phone) ? $request->phone : $ticket->phone;
            $comment = isset($request->comment) ? $request->comment : $ticket->comment;
            if ($ticket != null) {
                Queue::where('id', $ticket->id)->update(['reason_for_visit' => $reason, 'comment' => $comment, 'phone' => $phone]);
            } else {
                return response()->json(['already_executed' => true]);
            }
        } catch (\Exception $e) {
            Log::info('a', [$e->getMessage()]);
            return response()->json(['status_code' => 500]);
        }
        return  response()->json(['phone' => $phone, 'comment' => $comment, 'reason' => $reason]);
    }
}
