<?php

namespace App\Repositories;

use App\Models\Reason;

class ReasonRepository
{
    public function create($data)
    {
        
       $reason = Reason::create([
            'code' => $data['code'],
            'description' => $data['description'],
        ]);
        return $reason;

    }
    public function update($data,$reason)
    {
        $reason->name= $data['code'];
        $reason->address= $data['description'];
        $reason->save();
        return $reason;
    }
    public function delete($data,$reason)
    {
        $reason->delete();
    }
 }
