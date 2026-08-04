<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostelApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hostel_id',
        'reason',
        'preferred_roommate',
        'notes',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'session',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function allocation()
    {
        return $this->hasOne(Allocation::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
