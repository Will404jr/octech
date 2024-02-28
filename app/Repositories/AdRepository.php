<?php

namespace App\Repositories;

use App\Models\Ad;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdRepository
{

    public function getAllActiveAds()
    {
        return Ad::where('status', true)->get();
    }

    public function getAdById($id)
    {
        return Ad::find($id);
    }

    public function getAdByBranchId($branchId)
    {
        return Ad::where('branch_id', $branchId)->where('status', true)->get();
    }

    public function create($data)
    {
        $branchId = auth()->user()->branch_id;
        $path = (isset($data['ad_img']) && $data['ad_img']->isValid() ? $data['ad_img']->store('posts', 'public') : null);
        $ad = Ad::create([
            'name' => $data['name'],
            'branch_id' => $data['branch_id'],
            'ad_img' => $path,
            'status' => 1
        ]);
        return $ad;
    }
    public function update($data, $ad)
    {
        if(isset($data['ad_img']) && $data['ad_img']->isValid())
        {
            //delete old file
            if($ad->ad_img)
            {
                Storage::disk('public')->delete($ad->ad_img);
            }
            //store new file
            $path = $data['ad_img']->store('posts','public');
            $ad->ad_img = $path;
        }
        $branchId = auth()->user()->branch_id;
        $ad->name = $data['name'];
        $ad->ad_img = $path;
        $ad->branch_id  = $branchId;
        $ad->save();
        return $ad;
    }
    public function delete($data, $ad)
    {
        $ad->delete();
    }
}
