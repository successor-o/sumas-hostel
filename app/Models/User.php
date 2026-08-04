<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * A student account. Uses the "web" auth guard.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'matric_number',
        'email',
        'phone',
        'faculty',
        'level',
        'gender',
        'emergency_contact_name',
        'emergency_contact_phone',
        'avatar',
        'settings',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'settings' => 'array',
        'password' => 'hashed',
    ];

    public function applications()
    {
        return $this->hasMany(HostelApplication::class);
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }

    public function activeAllocation()
    {
        return $this->hasOne(Allocation::class)->where('status', 'active')->latestOfMany();
    }

    public function notifications()
    {
        return $this->hasMany(SystemNotification::class)->orderByDesc('created_at');
    }

    public function latestApplication()
    {
        return $this->hasOne(HostelApplication::class)->latestOfMany();
    }

    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim($this->name));
        $letters = array_map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)), array_slice($parts, 0, 2));

        return implode('', $letters) ?: 'ST';
    }
}
