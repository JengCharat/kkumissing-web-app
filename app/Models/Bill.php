<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'BillNo',
        'roomID',
        'tenantID',
        'BillDate',
        'damage_fee',
        'overdue_fee',
        'water_price',
        'electricity_price',
        'total_price',
        'status',
    ];

    protected $primaryKey = 'BillNo';

    public function room()
    {
        return $this->belongsTo(Room::class, 'roomID', 'roomID');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenantID', 'tenantID');
    }
}
