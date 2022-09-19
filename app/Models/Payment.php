<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Payment extends Model
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $fillable = [
        'user_id',
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
