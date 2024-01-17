<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Queue;
use App\Models\Reason;
use App\Repositories\ReasonRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

    public function __construct(QueueController $queues)
    {
        $this->queues = $queues;
    }
    public function index()
    {
        return view('queue.index', [
            'queues' => Queue::get()
        ]);
    }
}
