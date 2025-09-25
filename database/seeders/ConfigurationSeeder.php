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
                'value' => 'false',
            ],
            [
                'label' => 'Notifications Email',
                'code' => 'notifications_email',
                'value' => 'jreyes@saycocorporativo.com',
            ],
            [
                'label' => 'Nexus Endpoint',
                'code' => 'nexus_endpoint',
                'value' => 'https://nexus.igou.mx/v1/',
            ],
            [
                'label' => 'Nexus Endpoint sandbox',
                'code' => 'nexus_endpoint_sandbox',
                'value' => 'https://nexus.igou.mx/v1/',
            ],
            [
                'label' => 'Nexus Token',
                'code' => 'nexus_token',
                'value' => 'MjpDSjY0TDdZdzdYUTN6TUY5U3lNbmx4dmlvVEV2ZnNDUjF3ZHBoZFo1',
            ],
            [
                'label' => 'Nexus Token sandbox',
                'code' => 'nexus_token_sandbox',
                'value' => 'MjpDSjY0TDdZdzdYUTN6TUY5U3lNbmx4dmlvVEV2ZnNDUjF3ZHBoZFo1',
            ],
            [
                'label' => 'WhatsApp Token',
                'code' => 'whatsapp_token',
                'value' => 'EAARr3709tpsBPtWNcxyP3IfmAgzQXolDvVKvzXoAiWcw6o4W6goExyjrIFFjOl0VCFidPqxon4Ez73w4pGdZA8IsWPboZBHmCcguktt19D2qWZBZAK1rZCY1fiXMNbf2vDZCqpBB2vMXUYHSVVbKdiUWsUCZCLhxDfbSQsopM3ZBYVj2lGjZBTIZAwVD0wPMGpAQZDZD',
            ],
            [
                'label' => 'Id Phone Number WhatsApp',
                'code' => 'id_phone_number_whatsapp',
                'value' => '625587697313034',
            ],
            [
                'label' => 'Api WhatsApp',
                'code' => 'api_whatsapp',
                'value' => 'https://graph.facebook.com/v22.0/',
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
