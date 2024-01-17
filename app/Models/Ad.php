<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Ad extends Model
{
    protected $fillable = [
        'branch_id', 'name', 'ad_img', 'status',
    ];

    protected $appends = ['ad_img_url'];


    public function getAdImgUrlAttribute()
    {
        return Storage::disk('public')->url($this->ad_img);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
