<?php

namespace App\Repositories;

use App\Models\Branch;
use Illuminate\Support\Facades\Log;

class BranchRepository
{
    public function create($data)
    {
        
       $branch = Branch::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'local_network_address' => $data['local_network_address'],
            'db_host' => $data['db_host'],
            'db_name' => $data['db_name'],
            'db_username' => $data['db_user'],
            'db_password' => $data['db_password']
        ]);
        return $branch;

    }
    public function update($data,$branch)
    {
        $branch->name= $data['name'];
        $branch->address= $data['address'];
        $branch->local_network_address= $data['local_network_address'];
        $branch->db_host =  $data['db_host'];
        $branch-> db_name = $data['db_name'];
        $branch->db_username = $data['db_user'];
        $branch->db_password = $data['db_password'];
        $branch->save();
        return $branch;
    }
    public function delete($data,$branch)
    {
        $branch->delete();
    }
 }
