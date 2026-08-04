<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'warden',
        'description',
        'image',
        'status',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function applications()
    {
        return $this->hasMany(HostelApplication::class);
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }

    public function totalBeds(): int
    {
        return (int) $this->rooms()->sum('capacity');
    }

    public function occupiedBeds(): int
    {
        return (int) $this->allocations()->where('status', 'active')->count();
    }

    public function availableBeds(): int
    {
        return max(0, $this->totalBeds() - $this->occupiedBeds());
    }

    public function occupancyPercent(): int
    {
        $total = $this->totalBeds();

        return $total > 0 ? (int) round(($this->occupiedBeds() / $total) * 100) : 0;
    }

    public function occupancyStatusLabel(): string
    {
        $pct = $this->occupancyPercent();

        if ($pct >= 100) {
            return 'Full';
        }

        if ($pct >= 80) {
            return 'Limited';
        }

        return 'Available';
    }

    /**
     * Resolve a display URL for this hostel's image whether it was:
     *  - seeded with a bare filename referencing public/assets/images/*, or
     *  - uploaded by an admin and stored on the "public" disk (storage/app/public/hostels/*).
     */
    public function imageUrl(): string
    {
        if (! $this->image) {
            return asset('assets/images/campus-building.jpg');
        }

        if (str_contains($this->image, '/')) {
            return asset('storage/'.$this->image);
        }

        return asset('assets/images/'.$this->image);
    }
}
