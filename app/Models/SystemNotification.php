<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A simple in-app notification row shown in the notification centers.
 * Named "SystemNotification" (not "Notification") to avoid clashing with
 * Laravel's built-in Illuminate\Notifications\Notification class.
 */
class SystemNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_id',
        'title',
        'message',
        'type',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function iconFor(): string
    {
        return match ($this->type) {
            'success' => 'fa-circle-check',
            'warning' => 'fa-triangle-exclamation',
            'danger' => 'fa-circle-xmark',
            default => 'fa-circle-info',
        };
    }
}
