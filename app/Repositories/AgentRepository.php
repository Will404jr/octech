<?php

namespace App\Repositories;

use App\Models\Agent;
use App\Models\Branch;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class AgentRepository
{

    public function __construct()
    {
        
    }

    public function create($data)
    {
        $agent = Agent::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'title' => 'QMS-CSO',
            'id' => $data['user_name'],
            'branch' => $data['branch_id'],
        ]);
        return $agent;
    }
    public function update($data, $agent)
    {
        $agent->first_name = $data['first_name'];
        $agent->last_name = $data['last_name'];
        $agent->id = $data['user_name'];
        $agent->branch = $data['branch_id'];
        $agent->save();
        return $agent;
    }
    public function delete($data, $agent)
    {
        $agent->delete();
    }
}
