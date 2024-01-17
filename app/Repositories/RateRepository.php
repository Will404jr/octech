<?php

namespace App\Repositories;

use App\Models\Rate;
use Illuminate\Support\Facades\Storage;

class RateRepository
{
    public function getAllRates()
    {
        return Rate::get();
    }

    public function getAllActiveRates()
    {
        return Rate::where('status', true)->get();
    }

    public function getRateById($id)
    {
        return Rate::find($id);
    }
    public function create($data)
    {
        $branchId = auth()->user()->branch_id;
        $service = Rate::create([
            'country_name' => $data['country_name_text'],
            'country_code' => $data['country_code'],
            'currency_code' => $data['currency_code'],
            'country_flag' => $data['country_flag_text'],
            'branch_id' => $branchId,
            'buying_rate' => $data['buying_rate'],
            'selling_rate' => $data['selling_rate'],
            'status' => 1,
        ]);
        return $service;
    }
    public function update($data, $rate)
    {
        $branchId = auth()->user()->branch_id;
        $rate->country_name = $data['country_name_text'];
        $rate->country_code = $data['country_code'];
        $rate->currency_code = $data['currency_code'];
        $rate->country_flag = $data['country_flag_text'];
        $rate->branch_id = $branchId;
        $rate->buying_rate = $data['buying_rate'];
        $rate->selling_rate = $data['selling_rate'];
        $rate->save();
        return $rate;
    }
    public function delete($data, $rate)
    {
        $rate->delete();
    }
}
