<?php

namespace App\Repositories;

use App\Models\Service;
use Illuminate\Support\Facades\Storage;

class ServiceRepository
{
    public function getAllServices()
    {
        return Service::get();
    }

    public function getAllActiveServices()
    {
        return Service::where('status', true)->get();
    }

    public function getServiceById($id)
    {
        return Service::find($id);
    }
    public function create($data)
    {
        if (!isset($data['sms'])) $data['sms'] = false;
        $branchId = auth()->user()->branch_id;
        $service = Service::create([
            'name' => $data['name'],
            'letter' => $data['letter'],
            'branch_id' => $branchId,
            'start_number' => $data['start_number'],
            'status' => 1,
            'ask_phone' => $data['ask_phone'],
            'phone_required' => $data['ask_phone'] == 1 ?  $data['phone_required'] : false,
            'sms_enabled' => $data['ask_phone'] == 1 ? $data['sms'] : false,
            'optin_message_enabled' => ($data['ask_phone'] == 1 && $data['sms'] == 1) ? $data['optin_message'] : false,
            'call_message_enabled' => ($data['ask_phone'] == 1 && $data['sms'] == 1) ? $data['call_message'] : false,
            'noshow_message_enabled' => ($data['ask_phone'] == 1 && $data['sms'] == 1) ? $data['noshow_message'] : false,
            'completed_message_enabled' => ($data['ask_phone'] == 1 && $data['sms'] == 1) ? $data['completed_message'] : false,
            'status_message_enabled' => ($data['ask_phone'] == 1 && $data['sms'] == 1) ? $data['status_message'] : false,
            'optin_message_format' => ($data['ask_phone'] == 1 && $data['sms'] == 1 && $data['optin_message'] == 1) ? str_replace("'", "`", $data['optin_message_format']) : null,
            'call_message_format' => ($data['ask_phone'] == 1 && $data['sms'] == 1 && $data['call_message'] == 1) ? str_replace("'", "`", $data['call_message_format']) : null,
            'noshow_message_format' => ($data['ask_phone'] == 1 && $data['sms'] == 1 && $data['noshow_message'] == 1) ? str_replace("'", "`", $data['noshow_message_format']) : null,
            'completed_message_format' => ($data['ask_phone'] == 1 && $data['sms'] == 1 && $data['completed_message'] == 1) ? str_replace("'", "`", $data['completed_message_format']) : null,
            'status_message_format' => ($data['ask_phone'] == 1 && $data['sms'] == 1 && $data['status_message'] == 1) ? str_replace("'", "`", $data['status_message_format']) : null,
            'status_message_positions' => ($data['ask_phone'] == 1 && $data['sms'] == 1 && $data['status_message'] == 1) ?  $data['status_message_positions'] : null,
            'ask_gender' => $data['ask_gender'],
            'gender_required' => $data['ask_gender'] == 1 ? $data['gender_required'] : false,
            'ask_payment_mode' => $data['ask_payment_mode'],
            'payment_mode_required' => $data['ask_payment_mode'] == 1 ? $data['payment_mode_required'] : false,
        ]);
        return $service;
    }
    public function update($data, $service)
    {
        if (!isset($data['sms'])) $data['sms'] = false;
        $branchId = auth()->user()->branch_id;
        $service->name = $data['name'];
        $service->letter = $data['letter'];
        $service->start_number = $data['start_number'];
        $service->sms_enabled = $data['sms'];
        $service->branch_id  = $branchId;
        $service->optin_message_enabled = ($data['ask_phone'] == 1 && $data['sms'] == 1) ? $data['optin_message'] : false;
        $service->call_message_enabled = ($data['ask_phone'] == 1 && $data['sms'] == 1) ? $data['call_message'] : false;
        $service->noshow_message_enabled = ($data['ask_phone'] == 1 && $data['sms'] == 1) ? $data['noshow_message'] : false;
        $service->completed_message_enabled = ($data['ask_phone'] == 1 && $data['sms'] == 1) ? $data['completed_message'] : false;
        $service->status_message_enabled = ($data['ask_phone'] == 1 && $data['sms'] == 1) ? $data['status_message'] : false;
        $service->optin_message_format = ($data['ask_phone'] == 1 && $data['sms'] == 1 && $data['optin_message'] == 1) ? str_replace("'", "`", $data['optin_message_format']) : null;
        $service->call_message_format = ($data['ask_phone'] == 1 && $data['sms'] == 1 && $data['call_message'] == 1) ? str_replace("'", "`", $data['call_message_format']) : null;
        $service->noshow_message_format = ($data['ask_phone'] == 1 && $data['sms'] == 1 && $data['noshow_message'] == 1) ? str_replace("'", "`", $data['noshow_message_format'])  : null;
        $service->completed_message_format = ($data['ask_phone'] == 1 && $data['sms'] == 1 && $data['completed_message'] == 1) ? str_replace("'", "`", $data['completed_message_format']) : null;
        $service->status_message_format = ($data['ask_phone'] == 1 && $data['sms'] == 1 && $data['status_message'] == 1) ? str_replace("'", "`", $data['status_message_format']) : null;
        $service->status_message_positions = ($data['ask_phone'] == 1 && $data['sms'] == 1 && $data['status_message'] == 1) ? $data['status_message_positions'] : null;
        $service->ask_gender = $data['ask_gender'];
        $service->gender_required = ($data['ask_gender'] == 1) ? $data['gender_required'] : false;
        $service->ask_payment_mode = $data['ask_payment_mode'];
        $service->payment_mode_required = ($data['ask_payment_mode'] == 1) ? $data['payment_mode_required'] : false;
        $service->ask_phone = $data['ask_phone'];
        $service->phone_required = ($data['ask_phone'] == 1) ? $data['phone_required'] : false;
        $service->save();
        return $service;
    }
    public function delete($data, $service)
    {
        Storage::delete('public/service_' . $service->id . '_display.json');
        $service->delete();
    }
}
