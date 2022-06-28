<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;



class Schedule extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'time_slots' => 'array',
    ];


    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function timeslot()
    {
        return $this->hasMany(Timeslot::class);
    }
}