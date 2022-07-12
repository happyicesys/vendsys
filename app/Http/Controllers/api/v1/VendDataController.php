<?php

namespace App\Http\Controllers\api\v1;

use App\Models\PaymentMethod;
use App\Models\VendMachine;
use App\Models\VendMachineChannel;
use App\Models\VendMachineChannelError;
use App\Models\VendMachineChannelErrorLog;
use App\Models\VendMachineTempData;
use App\Models\VendMachineTransaction;

use App\Http\Controllers\Controller;
use App\Traits\HasIncrement;
use Carbon\Carbon;
use Illuminate\Http\Request;


class VendDataController extends Controller
{
    use HasIncrement;

    public function create(Request $request)
    {
        // dd($request->all());
        if($request->all()) {
            if($request->Vid) {
                $vendMachine = VendMachine::firstOrCreate([
                    'code' => $request->Vid,
                ]);

                if($vendMachine) {
                    if($coinAmount = $request->CoinCnt) {
                        $vendMachine->coin_amount = $coinAmount;
                    }

                    if($firmwareVer = $request->Ver) {
                        $vendMachine->firmware_ver = $firmwareVer;
                    }

                    if($isDoorOpen = $request->door) {
                        $vendMachine->firmware_ver = $firmwareVer;
                    }

                    if($isSensorNormal = $request->Sensor) {
                        $vendMachine->is_sensor_normal = $isSensorNormal;
                    }

                    // if($LstSltE = $request->LstSltE) {
                    //     $this->syncVendMachineChannelErrorLog($vendMachine, $LstSltE/1000, $LstSltE%1000);
                    // }

                    $vendMachine->save();

                    if($type = $request->Type) {
                        switch($type) {
                            case 'VENDER':
                                if($temp = $request->TEMP) {
                                    $this->createVendMachineTemp($vendMachine, $temp);
                                }
                                break;
                            case 'TRADE':
                                $this->createVendMachineTransaction($vendMachine, $request);
                                break;
                            case 'CHANNEL':
                                $this->syncVendMachineChannels($vendMachine, $request);
                                break;
                        }
                    }
                }
            }
        }
    }

    private function createVendMachineTemp(VendMachine $vendMachine, $temp)
    {
        // more than 3 minutes only update same machine temp
        if(!$vendMachine->temp_datetime or $vendMachine->temp_datetime->addMinutes(3)->isPast()) {
            $vendMachine->vendMachineTemps()->create([
                'temp' => $temp,
            ]);

            $vendMachine->temp = $temp;
            $vendMachine->temp_datetime = Carbon::now();
            $vendMachine->save();
        }
    }

    private function createVendMachineTransaction(VendMachine $vendMachine, $request)
    {
        if($payType = $request->PAY_TYPE) {
            $paymentMethod = PaymentMethod::where('code', $payType)->first();
        }

        if($sID = $request->SId) {
            $vendMachineChannel = VendMachineChannel::where('code', $sID)->where('vend_machine_id', $vendMachine->id)->first();

            if(!$vendMachineChannel) {
                $vendMachineChannel = VendMachineChannel::create([
                    'code' => $sID,
                    'vend_machine_id' => $vendMachine->id,
                ]);
            }
        }

        $vendMachineChannelError = VendMachineChannelError::where('code', $request->sErr)->first();

        VendMachineTransaction::create([
            'code' => $this->getVendMachineTransactionIncrement(),
            'order_id' => $request->ORDRID,
            'transaction_datetime' => $request->TIME,
            'amount' => $request->Price,
            'payment_method_id' => isset($paymentMethod) ? $paymentMethod->id : 0,
            'vend_machine_id' => $vendMachine->id,
            'vend_machine_channel_id' => isset($vendMachineChannel) ? $vendMachineChannel->id : 0,
            'vend_machine_channel_error_id' => isset($vendMachineChannelError) ? $vendMachineChannelError->id : null
        ]);

        if($vendMachineChannelError) {
            $this->syncVendMachineChannelErrorLog($vendMachine, $request->SId, $request->sErr);
        }
    }

    private function syncVendMachineChannels(VendMachine $vendMachine, $request)
    {
        if($channels = $request->channels) {
            foreach($channels as $channel) {
                if($channel['capacity'] > 0) {
                    VendMachineChannel::updateOrCreate([
                        'vend_machine_id' => $vendMachine->id,
                        'code' => $channel['channel_code'],
                    ], [
                        'qty' => $channel['qty'],
                        'capacity' => $channel['capacity'],
                        'amount' => $channel['amount'],
                    ]);
                    $this->syncVendMachineChannelErrorLog($vendMachine, $channel['channel_code'], $channel['error_code']);
                }else {
                    $zeroCapacityChannel = VendMachineChannel::where('vend_machine_id', $vendMachine->id)
                                                            ->where('code', $channel['channel_code'])
                                                            ->first();
                    if($zeroCapacityChannel) {
                        $zeroCapacityChannel->is_active = false;
                        $zeroCapacityChannel->save();
                    }
                }
            }
        }
    }

    private function getVendMachineTransactionIncrement()
    {
        $latestVendMachineTransaction = VendMachineTransaction::latest()->first();

        if($latestVendMachineTransaction) {
            return $this->getIncrementByYearMonth(currentNumber: $latestVendMachineTransaction->code);
        }else {
            return $this->getIncrementByYearMonth();
        }
    }

    private function syncVendMachineChannelErrorLog(VendMachine $vendMachine, $vendMachineChannelCode, $vendMachineChannelErrorCode)
    {
        $vendMachineChannel = VendMachineChannel::where('vend_machine_id', $vendMachine->id)->where('code', $vendMachineChannelCode)->first();
        if(!$vendMachineChannel) {
            $vendMachineChannel = VendMachineChannel::updateOrCreate([
                'vend_machine_id' => $vendMachine->id,
                'code' => $vendMachineChannelCode,
            ]);
        }
        $vendMachineChannelError = VendMachineChannelError::where('code', $vendMachineChannelErrorCode)->first();
        // dd($vendMachineChannelCode, $vendMachineChannelErrorCode, $vendMachineChannel->id, $vendMachineChannelError->id);

        if($vendMachineChannel and $vendMachineChannelError and $vendMachineChannelError->code != 0) {
            // dd($vendMachineChannelCode, $vendMachineChannelErrorCode, $vendMachineChannel->id, $vendMachineChannelError->id, '111');
            VendMachineChannelErrorLog::updateOrCreate([
                'vend_machine_channel_id' => $vendMachineChannel->id,
            ],[
                'vend_machine_channel_error_id' => $vendMachineChannelError->id
            ]);
        }elseif($vendMachineChannel and $vendMachineChannelError and $vendMachineChannelError->code == 0) {
            // dd($vendMachineChannelCode, $vendMachineChannelErrorCode, $vendMachineChannel->id, $vendMachineChannelError->id, '222');
            $vendMachineChannelErrorLog = VendMachineChannelErrorLog::where('vend_machine_channel_id', $vendMachineChannel->id)->first();

            if($vendMachineChannelErrorLog) {
                $vendMachineChannelErrorLog->delete();
            }
        }
    }
}
