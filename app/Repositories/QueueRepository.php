<?php

namespace App\Repositories;

use App\Models\Queue;
use App\Models\Reason;
use Carbon\Carbon;

class QueueRepository
{
    public function getAllActiveQueues()
    {
        return Queue::with('reason')->where('status', NULL)->get();
    }

    public function getQueueById($id)
    {
        return Queue::with('reason')->find($id);
    }

    public function create($data)
    {

        $reason = Queue::create([
            'code' => $data['code'],
            'description' => $data['description'],
        ]);
        return $reason;
    }
    public function update($data, $reason)
    {
        $reason->name = $data['code'];
        $reason->address = $data['description'];
        $reason->save();
        return $reason;
    }
    public function delete($data, $reason)
    {
        $reason->delete();
    }

    public function servedTicket(Queue $ticket)
    {

        $ticket->time_out = Carbon::now();
        $ticket->status = 'completed';
        $ticket->save();
        return $ticket;
    }

    public function noShowTicket(Queue $ticket)
    {
        $ticket->time_out = Carbon::now();
        $ticket->status = 'noshow';
        $ticket->save();
        return $ticket;
    }
}
