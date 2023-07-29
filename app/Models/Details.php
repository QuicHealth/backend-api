<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Details extends Model
{
    use HasFactory;

    protected $fillable = [
        "appointment_id",
        "purpose",
        "symptoms",
        "allergies",
        "medications",
        "others",
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
