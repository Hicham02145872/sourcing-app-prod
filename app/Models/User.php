<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, MustVerifyEmailTrait, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'email_verified_at',
        'fcm_token',
        'can_delete_clients',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verification_email_sent_at' => 'datetime',
            'password' => 'hashed',
            'can_delete_clients' => 'boolean',
        ];
    }

    public function sourcingRequests()
    {
        return $this->hasMany(SourcingRequest::class);
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'super_admin';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isDeveloper(): bool
    {
        return $this->role === 'developer';
    }

    public function sourcingOrders()
    {
        return $this->hasMany(SourcingOrder::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function assignedSourcingRequests()
    {
        return $this->hasMany(SourcingRequest::class, 'assigned_to_admin_id');
    }

    public function assignedSourcingOrders()
    {
        return $this->hasMany(SourcingOrder::class, 'assigned_to_admin_id');
    }

    public function userSessions()
    {
        return $this->hasMany(UserSession::class);
    }

    public function getRoleLabel(): string
    {
        return match ($this->role) {
            'super_admin' => 'Super Admin',
            'admin' => 'Administrator',
            'developer' => 'Developer',
            'client' => 'Client',
            default => ucfirst($this->role ?? 'User'),
        };
    }

    /**
     * Check if the user has permission to delete clients.
     */
    public function canDeleteClients(): bool
    {
        return $this->isSuperAdmin() || (bool) $this->can_delete_clients;
    }
}
