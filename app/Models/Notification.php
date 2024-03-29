<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'message',
        'title',
        'category',
        'read_reciept'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'user_id');
    }
}