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
        'month_rate',
    ];

    protected $primaryKey = 'roomID';

    public function contracts()
    {
        return $this->hasOne(Contract::class, 'room_id', 'roomID');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_id', 'roomID');
    }

    public function meterReadings()
    {
        return $this->hasOne(MeterReading::class, 'room_id', 'roomID');
    }

    public function expenses()
    {
        return $this->hasOne(Expense::class, 'room_id', 'roomID');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class, 'roomID', 'roomID');
    }

}
