<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;



class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'date'
    ];

    protected $table = 'schedules';


    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function timeslot()
    {
        return $this->hasMany(Timeslot::class);
    }
}
