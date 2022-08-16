<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'join_url'
    ];

    public function appointment()
    {
        return $this->belongsTo(Application::class);
    }
}