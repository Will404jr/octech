<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_id', 'branch_id', 'number','called','letter','reference_no','phone','payment_mode','gender','position'
    ];

    protected $appends = ['formated_date','token_number'];

    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function getFormatedDateAttribute($value){
        $date = Carbon::parse($this->created_at)->format('j-F-Y');
        return $date;
    }
    public function call(){
        return $this->hasOne(Call::class);
    }

    public function getTokenNumberAttribute(){
        return $this->letter.'-'.$this->number;
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
