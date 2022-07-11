<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendMachineTransaction extends Model
{
    use HasFactory;

    protected $casts = [
        'transaction_datetime' => 'datetime'
    ];

    protected $fillable = [
        'code',
        'order_id',
        'transaction_datetime',
        'amount',
        'payment_method_id',
        'vend_machine_channel_id',
        'vend_machine_channel_error_id',
        'vend_machine_id'
    ];

    // relationships
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function vendMachine()
    {
        return $this->belongsTo(VendMachine::class);
    }

    public function vendMachineChannel()
    {
        return $this->belongsTo(VendMachineChannel::class);
    }

    public function vendMachineChannelError()
    {
        return $this->belongsTo(VendMachineChannelError::class);
    }
}
