<?php

use App\Mail\Notification;
use App\Models\Campaign;
use App\Models\Configuration;
use App\Models\Recipient;
use Aws\Exception\AwsException;
use Aws\Sns\SnsClient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;

function getNexusConfig()
{
    try {
        $configKeys = [
            'is_sandbox',
            'nexus_token',
            'nexus_token_sandbox',
            'nexus_endpoint',
            'nexus_endpoint_sandbox'
        ];
        $configurations = Configuration::whereIn('code', $configKeys)->pluck('value', 'code');

        if ($configurations->count() < count($configKeys)) {
            throw new Exception('Missing Nexus configuration values');
        }

        $isSandbox = $configurations['is_sandbox'];
        $endpoint = $isSandbox ? $configurations['nexus_endpoint_sandbox'] : $configurations['nexus_endpoint'];
        $token = $isSandbox ? $configurations['nexus_token_sandbox'] : $configurations['nexus_token'];

        $response = Http::get($endpoint . "token", ["client_token" => $token]);
        $responseObject = json_decode($response);

        if ($responseObject->status === 'fail') {
            throw new Exception('Error retrieving token: ' . $responseObject->error);
        }

        return [
            'endpoint' => $endpoint,
            'token' => $responseObject->data->access_token
        ];
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

function getNexusResponse()
{
    try {
        $currentDateTime = Carbon::now();
        $hourAndMinute = $currentDateTime->format('H:i');
        $dayOfWeek = strtolower($currentDateTime->englishDayOfWeek);

        $campaign = Campaign::with('templates')
            ->where('days', 'like', '%' . $dayOfWeek . '%')
            ->where('hour', $hourAndMinute)
            ->where('is_active', 1)
            ->first();

        if (is_null($campaign)) {
            throw new Exception('No active campaign found for today');
        }

        $nexusConfig = getNexusConfig();
        if (isset($nexusConfig['error'])) {
            throw new Exception($nexusConfig['error']);
        }

        $response = Http::withHeaders(['Authorization' => 'Bearer ' . $nexusConfig['token']])
            ->get($nexusConfig['endpoint'] . $campaign->querydata->query);

        $collection = collect($response->json());
        if ($collection->isEmpty()) {
            throw new Exception('No data found from Nexus API');
        }

        $customResponse = $collection->map(function ($item) {
            return [
                'email' => $item['email'] ?? null,
                'msisdn' => $item['msisdn'] ?? null,
                'name' => $item['name'] ?? 'cliente'
            ];
        });

        // foreach ($customResponse as $recipient) {
        //     Recipient::create([
        //         'name' => $recipient['name'],
        //         'campaign_id' => $campaign->id,
        //         'email' => $recipient['email'],
        //         'msisdn' => $recipient['msisdn']
        //     ]);
        // }

        // for ($i = 0; $i < 10; $i++) {  // Se ejecutará 10 veces
        foreach ($customResponse as $recipient) {
            Recipient::create([
                'name' => $recipient['name'],
                'campaign_id' => $campaign->id,
                'email' => $recipient['email'],
                'msisdn' => $recipient['msisdn']
            ]);
        }
        // }


        return response()->json($customResponse, 201);
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

function sendNotification()
{
    $responseSendEmail = null;
    $responseSendWhatsapp = null;
    $responseSendSms = null;
    try {
        $recipients = Recipient::limit(10)->get();

        if ($recipients->isEmpty()) {
            return false; // No hay más datos
        }

        // $msisdnExamples = ['5542762991', '5581513788'];
        // $emailExamples = ['jreyes@igou.mx', 'amartinez@igou.mx'];

        foreach ($recipients as $key => $recipient) {
            // $recipient->msisdn = $msisdnExamples[$key] ?? $recipient->msisdn;
            // $recipient->email = $emailExamples[$key] ?? $recipient->email;

            foreach ($recipient->campaign->templates as $template) {
                $channelName = $template->channel->name;
                $placeholder = $template->placeholder;
                $recipientName = $recipient->name;

                if ($channelName == 'Email' && $recipient->email) {
                    $responseSendEmail = sendEmail($recipient->email, $placeholder, $template->name, $recipientName);
                }
                if ($channelName == 'WhatsApp' && $recipient->msisdn) {
                    $responseSendWhatsapp = sendWhatsapp($recipient->msisdn, $placeholder, $template->name, $recipientName);
                }
                if ($channelName == 'SMS' && $recipient->msisdn) {
                    $responseSendSms = sendSms($recipient->msisdn, $placeholder);
                }
            }

            $recipient->delete();
        }

        return true; // Procesó datos
    } catch (Exception $e) {
        return false; // Error
    }
}

function sendEmail($to, $message, $campaignName, $name = 'unknown')
{
    try {
        $response = Mail::to($to)->send(new Notification($name, $message, $campaignName));
        return 'Email sent successfully';
        // dd($response);
    } catch (Exception $e) {
        return 'Failed to send email: ' . $e->getMessage();
        // dd($e);
    }
}

function sendWhatsapp($msisdn, $message, $campaignName, $name = '')
{
    try {
        $configKeys = [
            'whatsapp_token',
            'id_phone_number_whatsapp',
            'api_whatsapp',
        ];
        $configurations = Configuration::whereIn('code', $configKeys)->pluck('value', 'code');

        // dd($configurations['api_whatsapp'] . $configurations['id_phone_number_whatsapp'] . '/messages');

        $client = new Client();
        $response = $client->post($configurations['api_whatsapp'] . $configurations['id_phone_number_whatsapp'] . '/messages', [
            'headers' => [
                'Authorization' => 'Bearer ' . $configurations['whatsapp_token'],
                'Content-Type' => 'application/json',
            ],
            'json' => [
                "messaging_product" => "whatsapp",
                "to" => "52" . $msisdn,
                "type" => "template",
                "template" => [
                    "name" => "igou_notifications",
                    "language" => [
                        "code" => "es_MX"
                    ],
                    "components" => [
                        [
                            "type" => "header",
                            "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => $campaignName
                                ]
                            ]
                        ],
                        [
                            "type" => "body",
                            "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => $name ? $name : 'Cliente'
                                ],
                                [
                                    "type" => "text",
                                    "text" => $message
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        return response()->json([
            'status' => 'success',
            'data' => json_decode($response->getBody(), true),
        ]);

        // dd(json_decode($response->getBody(), true));
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
        // dd($e->getMessage());
    }
}

function sendSms($msisdn, $message)
{
    if ($msisdn == '5542762991') {
        $msisdn = '5612426571';
    }

    $SnSclient = new SnsClient([
        'region' => env('AWS_DEFAULT_REGION'),
        'version' => '2010-03-31',
        'credentials' => [
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
        ]
    ]);

    try {
        $result = $SnSclient->publish([
            'MessageAttributes' => [
                'AWS.SNS.SMS.SMSType' => [
                    'DataType' => 'String',
                    'StringValue' => 'Promotional',
                ],
            ],
            'Message' => $message,
            'PhoneNumber' => '+52' . $msisdn,
        ]);
        return json_encode($result->toArray());
    } catch (AwsException $e) {
        return $e->getMessage();
    }
}
