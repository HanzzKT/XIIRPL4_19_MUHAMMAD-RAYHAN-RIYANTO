<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'customer_id');
    }
    
    // Accessor untuk nama dari user
    public function getNameAttribute()
    {
        return $this->user->name ?? 'Unknown';
    }
}
