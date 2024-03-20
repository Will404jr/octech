<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    protected $table = 'reasons_for_visit';
    protected $connection = 'mysqlqms';
    public $timestamps = false;
}
