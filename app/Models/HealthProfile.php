<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_group', 'genotype',
        'martial_status', 'medication',
        'family_medical_history', 'health_condition',
        'peculiar_cases', 'allergies',
        'Occupation', 'past_medical_history',
    ];

    /**
     * Get the user that owns the phone.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}