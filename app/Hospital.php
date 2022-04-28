<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Hospital extends Authenticatable implements JWTSubject
{
    use SoftDeletes, HasFactory;

    protected $appends = ['doctors'];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function getDoctorsAttribute()
    {
        return $this->doctors()->get();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}