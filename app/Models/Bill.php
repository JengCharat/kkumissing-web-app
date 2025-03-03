<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    //
    //

    use HasFactory;
    protected $fillable = [
        'billID',
        'rent',
        'water_price',
        'electricity_price',
        'tenant_id',
        'total_price'
    ];

    protected $primaryKey = 'billID';

    public function rooms()
    {
        return $this->hasOne(Room::class, 'daily_rate', 'rent');
    }
    public function roomWaterPrice()
    {
        return $this->hasOne(Room::class, 'water_price', 'water_price');
    }

    public function roomElectricityPrice()
    {
        return $this->hasOne(Room::class, 'electricity_price', 'electricity_price');
    }
    public function tenants()
    {
        return $this->hasOne(Tenant::class, 'TenantID', 'tenant_id');
    }


}
