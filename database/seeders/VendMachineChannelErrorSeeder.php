<?php

namespace Database\Seeders;

use App\Models\VendMachineChannelError;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendMachineChannelErrorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VendMachineChannelError::create([
            'code' => 0,
            'desc' => 'No Malfunction (0)',
        ]);

        VendMachineChannelError::create([
            'code' => 1,
            'desc' => 'Index Error (1)',
        ]);

        VendMachineChannelError::create([
            'code' => 4,
            'desc' => 'Open circuit, motor not detected (4)',
        ]);

        VendMachineChannelError::create([
            'code' => 5,
            'desc' => 'Current overlimit (5)',
        ]);

        VendMachineChannelError::create([
            'code' => 6,
            'desc' => 'Microswitch pressed over time (6)',
        ]);

        VendMachineChannelError::create([
            'code' => 7,
            'desc' => 'Sensor error (7)',
        ]);

        VendMachineChannelError::create([
            'code' => 42,
            'desc' => 'Motor board communication error (42)',
        ]);

        VendMachineChannelError::create([
            'code' => 45,
            'desc' => 'Motor board is not connected or does not start (45)',
        ]);

        VendMachineChannelError::create([
            'code' => 3,
            'desc' => 'Microswith not detected (3)',
        ]);

        VendMachineChannelError::create([
            'code' => 77,
            'desc' => 'Drop sensor error (77)',
        ]);

        VendMachineChannelError::create([
            'code' => 9,
            'desc' => 'Sensor error and disabled (9)',
        ]);
    }
}
