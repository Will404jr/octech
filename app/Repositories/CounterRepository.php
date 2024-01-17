<?php

namespace App\Repositories;

use App\Models\Counter;
use Illuminate\Support\Facades\Log;

class CounterRepository
{

    public function getAllActiveCounters()
    {
        return Counter::where('status', true)->get();
    }

    public function getCounterById($id)
    {
        return Counter::find($id);
    }

    public function create($data)
    {
        $branchId = auth()->user()->branch_id;
        $counter = Counter::create([
            'name' => $data['name'],
            'branch_id' => $branchId,
            'status' => 1
        ]);
        return $counter;
    }
    public function update($data, $counter)
    {
        $branchId = auth()->user()->branch_id;
        $counter->name = $data['name'];
        $counter->branch_id  = $branchId;
        $counter->save();
        return $counter;
    }
    public function delete($data, $counter)
    {
        $counter->delete();
    }
}
