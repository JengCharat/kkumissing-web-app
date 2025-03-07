<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenantID',
        'tenantName',
        'tenant_type',
        'telNumber',
    ];

    protected $primaryKey = 'tenantID';

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'tenant_id', 'tenantID');
    }

    public function meterReadings()
    {
        return $this->hasOne(MeterReading::class, 'tenant_id', 'tenantID');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'tenant_id', 'tenantID');
    }
    public function users(){
        return $this->belongsTo(User::class,'user_id_tenant','id');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class, 'tenantID', 'tenantID');
    }
}
