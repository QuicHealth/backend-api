<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class Doctor extends Authenticatable implements JWTSubject
{
    use SoftDeletes, HasFactory, HasRelationships;

    protected $guarded = [];

    protected $hidden = [
        'password', 'remember_token', 'deleted_at', 'created_at', 'updated_at'
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

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function getDoctorsAttribute()
    {
        return $this->doctors()->get();
    }

    /**
     * Get the Notifications associated with the user.
     */
    public function notification()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function account()
    {
        return $this->hasOne(Account::class);
    }

    public function profile()
    {
        return $this->hasOne(DoctorProfile::class);
    }
}
