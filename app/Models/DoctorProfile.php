<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'bio',
        'fee',
        'hospital'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
