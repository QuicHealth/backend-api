<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Payment extends Model
{
    use HasFactory, Notifiable;

    // protected $guarded = [];

    protected $fillable = [
        'user_id',
        'appointments_id',
        'amount',
        'paymentStatus',
        'tx_ref',
        'transaction_id',
        'charged_amount',
        'processor_response',
        'payment_gateway_type',
        'currency'
    ];

    /**
     * Get the user that owns the appointment.
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function routeNotificationForMail($notification)
    {
        // Return email address only...
        return $this->user->email;
    }
}