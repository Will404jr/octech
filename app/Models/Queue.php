<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $table = 'member_visits';
    protected $connection = 'mysqlqms';
    public $timestamps = false;
    protected $fillable = [
        'service_start', 'time_out', 'status', 'agent_assigned', 'comment',
    ];


    public function reason()
    {
        return $this->belongsTo(Reason::class, 'reason_for_visit');
    }
}
