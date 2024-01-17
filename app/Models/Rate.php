<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_name', 'country_code', 'currency_code', 'country_flag', 'branch_id', 'status', 'buying_rate', 'selling_rate'
    ];


    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
