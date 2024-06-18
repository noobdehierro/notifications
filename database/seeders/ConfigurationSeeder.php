<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dt = Carbon::now();
        $dateNow = $dt->toDateTimeString();

        $configurations = [
            [
                'label' => 'Sandbox',
                'code' => 'is_sandbox',
                'value' => 'true',
            ],
            [
                'label' => 'Notifications Email',
                'code' => 'notifications_email',
                'value' => 'jreyes@saycocorporativo.com',
            ],
            [
                'label' => 'Nexus Endpoint',
                'code' => 'nexus_endpoint',
                'value' => 'http://nexus.test/v1/',
            ],
            [
                'label' => 'Nexus Endpoint sandbox',
                'code' => 'nexus_endpoint_sandbox',
                'value' => 'http://nexus.test/v1/',
            ],
        ];

        foreach ($configurations as $configuration) {
            DB::table('configurations')->insert([
                'label' => $configuration['label'],
                'code' => $configuration['code'],
                'value' => $configuration['value'],
                'created_at' => $dateNow,
                'updated_at' => $dateNow
            ]);
        }
    }
}
