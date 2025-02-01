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
        'damage_fee',
        'overdue_fee',
        'due_date',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenantID');
    }

    // If Booking is related to Expense (based on ERD and 'record' relationship)
    // public function expenses()
    // {
    //     return $this->belongsToMany(Expense::class); // Assuming many-to-many relationship via 'record' in ERD
    // }
}
