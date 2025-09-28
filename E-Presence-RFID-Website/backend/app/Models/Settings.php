<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'service',
        'api_key',
        'connected_at',
        'attendance_method',
        'face_recognition_enabled',
        'anti_spoofing_enabled',
        'face_confidence_threshold',
    ];
    
    protected $casts = [
        'connected_at' => 'datetime',
        'face_recognition_enabled' => 'boolean',
        'anti_spoofing_enabled' => 'boolean',
        'face_confidence_threshold' => 'float',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
