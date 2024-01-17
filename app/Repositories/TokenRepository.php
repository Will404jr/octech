<?php

namespace App\Repositories;

use App\Models\Call;
use App\Models\Queue;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TokenRepository
{
    public function createToken(Service $service, $data, $is_details)
    {
        $last_token = Queue::where('created_at', '>=', Carbon::now()->startOfDay())->where('created_at', '<', Carbon::now()->endOfDay())->where('service_id', $service->id)->orderBy('created_at', 'desc')->first();
        if ($last_token) $token_number = $last_token->number + 1;
        else $token_number = $service->start_number;
        $branchId = Setting::all()->first()->branch_id;
        $queue = Queue::create([
            'service_id' => $service->id,
            'number' => $token_number,
            'called' => false,
            'branch_id' => $branchId,
            'reference_no' => Str::random(9),
            'letter' => $service->letter,
            'gender' => ($is_details && $service->ask_gender == 1) ? ($data['gender'] == '0' ? 'Male' : 'Female') : null,
            'payment_mode' => ($is_details && $service->ask_payment_mode == 1) ? ($data['payment_mode'] == '0' ? 'Cash' : 'Insurance') : null,
            'phone' => ($is_details && $service->ask_phone == 1) ? $data['phone'] : null,
            'position' => $this->customerWaiting($service) + 1
        ]);
        return $queue;
    }

    public function customerWaiting(Service $service)
    {
        $count = Queue::where('created_at', '>=', Carbon::now()->startOfDay())->where('created_at', '<=', Carbon::now())
            ->where('called', false)->where('service_id', $service->id)->count();
        return $count;
    }

    public function getTokensForCall($service)
    {
        $tokens = Queue::where('created_at', '>=', Carbon::now()->startOfDay())->where('created_at', '<=', Carbon::now())
            ->where('called', false)->where('service_id', $service->id)->get()->toArray();
        return $tokens;
    }

    public function getCalledTokens($service, $counter)
    {
        $tokens =  Call::where('created_at', '>=', Carbon::now()->startOfDay())->where('created_at', '<=', Carbon::now())
            ->where('service_id', $service->id)->where('counter_id', $counter->id)->orderByDesc('created_at')->get()->toArray();
        return $tokens;
    }

    public function transferToken($service, $counter, $token)
    {
        $queuePosition = Queue::where([['service_id', $token['service_id']]])->orderByDesc('created_at')->first()->position;
        $queue = Queue::where('id', $token['queue_id'])->first();
        $queue->called = 0;
        $queue->created_at = date("Y-m-d H:i:s");
        $queue->position = $queuePosition;
        $letter = Service::where('id', $token['service_id'])->first()->letter;
        $queue->letter = $letter;
        $queue->service_id = $service->id;
        $queue->save();
        try {
            $call =  Call::where('id', $token['id'])->first();
            $call->delete();
        } catch (Exception $e) {
            Log::info('exception', [$e->getMessage()]);
        }
    }

    public function editToken($request)
    {
        try {
            $queue = Queue::where('id', $request->token['queue_id'])->first();
            if(isset($request->gender)) $queue->gender = $request->gender == '0' ? 'Male' : 'Female';
            if(isset($request->phone)) $queue->phone = $request->phone;
            if(isset($request->payment_mode)) $queue->payment_mode = $request->payment_mode == '0' ? 'Cash' : 'Insurance';
            // if ($request->reason != $request->token['service_id']) {
            //     $queuePosition = Queue::where([['service_id', $request->token['service_id']]])->orderByDesc('created_at')->first()->position;
            //     $queue->called = 0;
            //     $queue->created_at = date("Y-m-d H:i:s");
            //     $queue->position = $queuePosition;
            // }
            if(isset($request->reason)) $queue->service_id = $request->reason;
            $queue->save();

            $call = Call::where('id', $request->token['id'])->first();
            // if ($request->reason != $request->token['service_id']) {
            //     $call->delete();
            // } else {
            $call = Call::where('id', $request->token['id'])->first();
            $call->service_id = $request->reason;
            $call->save();
            //}
        } catch (Exception $e) {
            Log::info('exception', [$e->getMessage()]);
        }
    }

    public function setTokensOnFile()
    {
        $tokens_for_call = Queue::where('created_at', '>=', Carbon::now()->startOfDay())->where('created_at', '<=', Carbon::now())
            ->where('called', false)->get()->toArray();
        $called_tokens =  Call::where('created_at', '>=', Carbon::now()->startOfDay())->where('created_at', '<=', Carbon::now())
            ->orderByDesc('created_at')->get()->toArray();
        $data['tokens_for_call'] = $tokens_for_call;
        $data['called_tokens'] = $called_tokens;
        Storage::put('public/tokens_for_callpage.json', json_encode($data));
    }
}
