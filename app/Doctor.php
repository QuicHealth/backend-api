<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Doctor extends Model
{
    use SoftDeletes, HasFactory;

    protected $appends = ['hospital', 'specialties'];

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
}
