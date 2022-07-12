<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendMachineChannel extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'qty',
        'capacity',
        'amount',
        'is_active',
        'vend_machine_id',
    ];

    // relationships
    public function vendMachine()
    {
        return $this->belongsTo(VendMachine::class);
    }
}
