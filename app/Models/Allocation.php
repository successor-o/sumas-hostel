<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hostel_id',
        'room_id',
        'hostel_application_id',
        'bed_number',
        'session',
        'status',
        'allocated_by',
        'allocated_at',
    ];

    protected $casts = [
        'allocated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function application()
    {
        return $this->belongsTo(HostelApplication::class, 'hostel_application_id');
    }

    public function allocatedBy()
    {
        return $this->belongsTo(Admin::class, 'allocated_by');
    }
}
