<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Appointment extends Model
{
    use Notifiable;
    // STATUS
    // 0 - pending
    // 1 - Successful
    // 2 - Cancelled
    // protected $guarded = [];

    protected $fillable =
    [
        'user_id',
        'doctor_id',
        'date',
        'start',
        'end',
        'status',
        'payment_status',
        'unique_id'
    ];

    // protected $appends = ['day'];

    // public function getDayAttributes()
    // {
    //     $day = DB::table('days')->where('id', $this->day_id)->first();
    //     return $day->name;
    // }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function details()
    {
        return $this->hasOne(Details::class);
    }

    /**
     * Get the user that owns the appointment.
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function zoomMeeting()
    {
        return $this->hasOne(Zoom::class);
    }

    public function report()
    {
        return $this->hasOne(Report::class);
    }

    public function routeNotificationForMail($notification)
    {
        // Return email address only...
        return $this->user->email;
    }
}