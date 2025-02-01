<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expenseID',
        'unit_price',
        'water',
        'electricity',
    ];

    // If Expense is related to Room (based on ERD and 'record' relationship)
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'roomID');
    }
}
