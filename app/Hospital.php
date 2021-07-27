<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use SoftDeletes;

    protected $appends = ['doctors'];

    public function doctors(){
        return $this->hasMany(Doctor::class);
    }

    public function getDoctorsAttribute(){
        return $this->doctors()->get();
    }
}
