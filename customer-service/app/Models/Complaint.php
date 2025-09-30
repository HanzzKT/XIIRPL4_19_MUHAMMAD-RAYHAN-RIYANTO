<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Customer;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'complaint_category_id',
        'description',
        'customer_phone',
        'status',
        'source',
        'handled_by',
        'resolved_by',
        'resolved_at',
        'verified_by',
        'verified_at',
        'action_notes',
        'cs_response',
        'cs_response_updated_at',
        'verification_status',
        'feedback_read_by_customer',
        'feedback_read_at',
        'created_by',
        'escalation_to',
        'escalated_at',
        'escalation_reason',
        'escalated_by',
        'manager_action',
        'manager_notes',
        'manager_action_at',
        'manager_action_by',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'verified_at' => 'datetime',
        'escalated_at' => 'datetime',
        'cs_response_updated_at' => 'datetime',
        'feedback_read_at' => 'datetime',
        'feedback_read_by_customer' => 'boolean',
        'manager_action_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ComplaintCategory::class, 'complaint_category_id');
    }

    public function handledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function escalatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'escalated_by');
    }

    public function escalatedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'escalation_to');
    }

    public function managerActionBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_action_by');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'baru' => 'bg-red-100 text-red-800',
            'diproses' => 'bg-yellow-100 text-yellow-800',
            'selesai' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
