<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeterDetails extends Model
{
    use HasFactory;

    protected $primaryKey = 'meter_detailID';

    protected $fillable = [
        'meter_detailID',
        'water_meter_start',
        'water_meter_end',
        'electricity_meter_start',
        'electricity_meter_end',
    ];

    public function meterReadings()
    {
        return $this->hasOne(MeterReading::class, 'meter_details_id', 'meter_detailID');
    }

}
