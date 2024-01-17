<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','branch_id','letter','start_number','status','sms_enabled','optin_message_enabled','call_message_enabled','noshow_message_enabled','completed_message_enabled','optin_message_format','call_message_format','noshow_message_format','completed_message_format','ask_gender','gender_required','ask_payment_mode','payment_mode_required','ask_phone','phone_required','status_message_enabled','status_message_format','status_message_positions'
    ];


    protected $casts = [
        'status_message_positions' => 'array',
    ];

    public function queues(){
        return $this->hasMany(Queue::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    // public function getStatusPositionsAttribute(){
    //     if($this->status_message_positions){
    //         json_decode($this->status_message_positions, true);
    //     }
    //     else null;
    // }
}
