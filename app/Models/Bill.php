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
        'roomID',
        'tenant_id',
        'total_price'
    ];

    protected $primaryKey = 'billID';

    public function rooms()
    {
        return $this->hasOne(Room::class, 'roomID', 'roomID');
    }
    public function tenants()
    {
        return $this->hasOne(Tenant::class, 'TenantID', 'tenant_id');
    }


}
