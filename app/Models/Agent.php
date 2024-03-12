<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Agent extends Model
{

    protected $table = 'agents';
    protected $connection = 'mysqlqms';
    public $timestamps = false;

    public $incrementing = false;
    protected $fillable = [
        'branch', 'first_name', 'last_name', 'title', 'id',
    ];

}
