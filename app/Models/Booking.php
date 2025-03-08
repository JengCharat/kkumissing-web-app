<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'bookingID',
        'room_id',
        'tenant_id',
        'booking_type',
        'check_in',
        'check_out',
        'deposit',
        'due_date',
    ];

    protected $primaryKey = 'bookingID';

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenantID');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'roomID');
    }
}
