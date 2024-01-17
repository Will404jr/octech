<?php

namespace App\Repositories;

use App\Models\Branch;


class BranchRepository
{
    public function create($data)
    {
        
       $branch = Branch::create([
            'name' => $data['name'],
            'address' => $data['address'],
        ]);
        return $branch;

    }
    public function update($data,$branch)
    {
        $branch->name= $data['name'];
        $branch->address= $data['address'];
        $branch->save();
        return $branch;
    }
    public function delete($data,$branch)
    {
        $branch->delete();
    }
 }
