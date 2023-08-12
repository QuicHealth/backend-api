<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank',
        'account_name',
        'account_number',
        'amount',
        'doctor_id'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
