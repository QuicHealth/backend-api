<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Doctor extends Authenticatable implements JWTSubject
{
    use SoftDeletes, HasFactory;

    protected $appends = ['hospital', 'specialties'];

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

    public function getHospitalAttribute()
    {
        return DB::table('hospitals')->where('id', $this->hospital_id)
            ->where('deleted_at', null)
            ->first();
    }

    public function getSpecialtiesAttribute()
    {
        return DB::table('specialties')->where('id', $this->specialty)->first();
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }

    public function getDoctorsAttribute()
    {
        return $this->doctors()->get();
    }
}