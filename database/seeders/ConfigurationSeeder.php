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
                'label' => 'Placeholder Endpoint',
                'code' => 'placeholder_endpoint',
                'value' => 'https://jsonplaceholder.typicode.com/',
            ],
            [
                'label' => 'Placeholder Endpoint sandbox',
                'code' => 'placeholder_endpoint_sandbox',
                'value' => 'https://jsonplaceholder.typicode.com/',
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
