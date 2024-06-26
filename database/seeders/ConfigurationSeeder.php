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
            [
                'label' => 'Nexus Token',
                'code' => 'nexus_token',
                'value' => 'MTphNW5QQTZtMHJSMWlCTVl2REpoUWFUcDQxUlA0WTBsU0ZFU2FQdktB',
            ],
            [
                'label' => 'Nexus Token sandbox',
                'code' => 'nexus_token_sandbox',
                'value' => 'MTphNW5QQTZtMHJSMWlCTVl2REpoUWFUcDQxUlA0WTBsU0ZFU2FQdktB',
            ],
            [
                'label' => 'WhatsApp Token',
                'code' => 'whatsapp_token',
                'value' => 'EAAGxrumaxTcBO98CHoZAzH0LS3fOb6O7nHTdAMHUFGB79MZBRy4hf0Avl9MMYb3dPLYfI9HUgXVYmnH2PWbR9D4d3M2AlpwgIdo2A8g9I4aMKHOsSwJDnWFZCNAe22KfZAcmMRC7C7dCFmraKAEUXkt19EckOZC5mhTTErIAcOWMzZCrKIRMnDzUA3zOnhMNZBTGnDS0CacSO7sSKuWDVthfAuW0U4ZD',
            ],
            [
                'label' => 'Id Phone Number WhatsApp',
                'code' => 'id_phone_number_whatsapp',
                'value' => '338995869302301',
            ],
            [
                'label' => 'Api WhatsApp',
                'code' => 'api_whatsapp',
                'value' => 'https://graph.facebook.com/v19.0/',
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
