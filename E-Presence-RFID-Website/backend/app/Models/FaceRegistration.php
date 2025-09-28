<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceRegistration extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'face_data',
        'face_data_2',
        'face_data_3',
        'status',
        'approved_by',
        'approved_at',
        'notes',
        'anti_spoofing_score',
        'confidence_score',
    ];
    
    protected $casts = [
        'approved_at' => 'datetime',
        'anti_spoofing_score' => 'float',
        'confidence_score' => 'float',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    public function isApproved()
    {
        return $this->status === 'approved';
    }
    
    public function isPending()
    {
        return $this->status === 'pending';
    }
    
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
