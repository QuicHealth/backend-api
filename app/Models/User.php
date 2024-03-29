<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'gender', 'phone', 'dob', 'auth_type'
    ];
    protected $hidden = [
        'password', 'remember_token', 'deleted_at', 'created_at', 'updated_at'
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function fullname()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * Get the Health Profile associated with the user.
     */
    public function HealthProfile()
    {
        return $this->hasOne(HealthProfile::class);
    }

    /**
     * Get the Appointments associated with the user.
     */

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the Notifications associated with the user.
     */
    public function notification()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function routeNotificationForMail($notification)
    {
        // Return email address only...
        return $this->email;
    }

    public function UserOtp()
    {
        return $this->hasMany(UserOtp::class);
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        
        $this->notify(new ResetPasswordNotification($token, $this->fullname));
    }
}
