<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeterReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'meterID',
        'room_id',
        'reading_date',
        'water_meter',
        'electricity_meter',
        'start_date',
        'end_date',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'roomID');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenantID');
    }
}
