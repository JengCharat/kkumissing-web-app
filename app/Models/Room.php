<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'roomID',
        'roomNumber',
        'status',
        'daily_rate',
        'water_price',
        'electricity_price',
        'overdue_fee_rate',
    ];

    public function contracts()
    {
        return $this->hasOne(Contract::class, 'room_id', 'roomID');
    }

    public function meterReadings()
    {
        return $this->hasOne(MeterReading::class, 'room_id', 'roomID');
    }

    public function expenses()
    {
        return $this->hasOne(Expense::class, 'room_id', 'roomID');
    }

}
