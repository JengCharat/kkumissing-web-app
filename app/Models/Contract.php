<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contractID',
        'room_id',
        'tenant_id',
        'contract_date',
        'contract_file',
        'start_date',
        'end_date',
    ];

    protected $primaryKey = 'contractID';

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'roomID');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenantID');
    }
}
