<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendMachineTemp extends Model
{
    use HasFactory;

    protected $fillable = [
        'vend_machine_id',
        'temp'
    ];

    // relationships
    public function vendMachine()
    {
        return $this->belongsTo(VendMachine::class);
    }
}
