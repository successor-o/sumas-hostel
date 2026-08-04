<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * A hostel office / system administrator account. Uses the "admin" auth guard.
 */
class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'staff_id',
        'role',
        'avatar',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function reviewedApplications()
    {
        return $this->hasMany(HostelApplication::class, 'reviewed_by');
    }

    public function allocationsMade()
    {
        return $this->hasMany(Allocation::class, 'allocated_by');
    }

    public function notifications()
    {
        return $this->hasMany(SystemNotification::class)->orderByDesc('created_at');
    }

    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim($this->name));
        $letters = array_map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)), array_slice($parts, 0, 2));

        return implode('', $letters) ?: 'AD';
    }
}
