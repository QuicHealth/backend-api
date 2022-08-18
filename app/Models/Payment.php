<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'appointments_id',
        'customer_name',
        'customer_email',
        'amount',
        'paymentStatus',
        'tx_ref',
        'transaction_id',
        'charged_amount',
        'processor_response',
    ];
}