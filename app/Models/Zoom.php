<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zoom extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $fillable = [
        'user_id',
        'doctor_id',
        'appointment_id',
        'meeting_id',
        'topic',
        'start_at',
        'duration',
        'password',
        'start_url',
        'join_url',
        'status' // pending, ongoing, cancelled, passed
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
