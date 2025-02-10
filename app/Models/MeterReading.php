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
        'tenant_id',
        'reading_date',
        'meter_details_id',
        'start_date',
        'end_date',
    ];
    protected $primaryKey = 'meterID';

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'roomID');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenantID');
    }
    public function meterdetails()
    {
        return $this->belongsTo(MeterDetails::class, 'meter_details_id', 'meter_detailID');
    }
}
