<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendMachineChannelErrorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'vend_machine_channel_id',
        'vend_machine_channel_error_id',
    ];

    // relationships
    public function vendMachineChannel()
    {
        return $this->belongsTo(VendMachineChannel::class);
    }

    public function vendMachineChannelError()
    {
        return $this->belongsTo(VendMachineChannelError::class);
    }
}
