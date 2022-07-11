<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendMachine extends Model
{
    use HasFactory;

    protected $casts = [
        'temp_datetime' => 'datetime'
    ];

    protected $fillable = [
        'code',
        'name',
        'temp',
        'temp_datetime',
        'coin_amount',
        'firmware_ver',
        'is_door_open',
        'is_sensor_normal',
    ];

    // relationships
    public function VendMachineTemps()
    {
        return $this->hasMany(VendMachineTemp::class);
    }
}
