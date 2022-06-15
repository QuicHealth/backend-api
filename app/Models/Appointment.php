<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Appointment extends Model
{
    // STATUS
    // 0 - pending
    // 1 - Successful
    // 2 - Cancelled
    protected $guarded = [];

    // protected $appends = ['day'];

    // public function getDayAttributes()
    // {
    //     $day = DB::table('days')->where('id', $this->day_id)->first();
    //     return $day->name;
    // }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_unique_id');
    }

    public function details()
    {
        return $this->hasOne(Details::class);
    }
}