<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    // protected $fillable = [
    //     'appointment_id',
    //     'transactionReference',
    //     'paymentReference',
    //     'amountPaid',
    //     'totalPayable',
    //     'settlementAmount',
    //     'paidOn',
    //     'paymentStatus',
    //     'paymentDescription',
    //     'transactionHash',
    //     'currency',
    //     'paymentMethod',
    // ];
}
