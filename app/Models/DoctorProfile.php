<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'bio',
        'fee',
        'hospital'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
