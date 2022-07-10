<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $table = "settings";

    protected $fillable = [
        'hospital_id',
        'bank',
        'acc_no',
        'acc_name',
        'amount',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}