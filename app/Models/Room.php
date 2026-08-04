<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostel_id',
        'room_number',
        'floor',
        'capacity',
        'status',
    ];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }

    public function activeAllocations()
    {
        return $this->hasMany(Allocation::class)->where('status', 'active');
    }

    public function occupiedBeds(): int
    {
        return $this->activeAllocations()->count();
    }

    public function availableBeds(): int
    {
        return max(0, $this->capacity - $this->occupiedBeds());
    }

    public function isFull(): bool
    {
        return $this->availableBeds() <= 0;
    }

    /**
     * Recalculate and persist this room's status based on live occupancy.
     * Skips rooms that are manually flagged for maintenance.
     */
    public function refreshStatus(): void
    {
        if ($this->status === 'maintenance') {
            return;
        }

        $occupied = $this->occupiedBeds();

        $status = match (true) {
            $occupied <= 0 => 'vacant',
            $occupied >= $this->capacity => 'full',
            default => 'partial',
        };

        if ($status !== $this->status) {
            $this->update(['status' => $status]);
        }
    }
}
