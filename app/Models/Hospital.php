<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Hospital extends Authenticatable implements JWTSubject
{
    use SoftDeletes, HasFactory;

    protected $appends = ['doctors'];

    protected $fillable = [
        'name', 'email', 'phone', 'image', 'featured', 'status', 'latitude', 'city', 'state', 'country', 'address', 'description'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get doctors associated with the hospital.
     */
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function getDoctorsAttribute()
    {
        return $this->doctors()->get();
    }

    public function settings()
    {
        return $this->hasMany(Settings::class);
    }

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
}
