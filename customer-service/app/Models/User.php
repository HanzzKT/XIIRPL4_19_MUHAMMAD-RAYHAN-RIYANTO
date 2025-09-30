<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'address',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isCs(): bool
    {
        return $this->role === 'cs';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'customer_id');
    }

    public function handledComplaints()
    {
        return $this->hasMany(Complaint::class, 'handled_by');
    }

    public function resolvedComplaints()
    {
        return $this->hasMany(Complaint::class, 'resolved_by');
    }

    public function createdComplaints()
    {
        return $this->hasMany(Complaint::class, 'customer_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
}
