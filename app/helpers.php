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

function getNexusConfig()
{
    try {
        $configuration = Configuration::whereIn('code', [
            'is_sandbox',
            'nexus_token',
            'nexus_token_sandbox',
            'nexus_endpoint',
            'nexus_endpoint_sandbox'
        ])->get();

        $configMap = $configuration->pluck('value', 'code');

        $is_sandbox = $configMap['is_sandbox'] ?? null;
        $nexus_token = $configMap['nexus_token'] ?? null;
        $nexus_token_sandbox = $configMap['nexus_token_sandbox'] ?? null;
        $nexus_endpoint = $configMap['nexus_endpoint'] ?? null;
        $nexus_endpoint_sandbox = $configMap['nexus_endpoint_sandbox'] ?? null;

        if (is_null($is_sandbox) || is_null($nexus_token) || is_null($nexus_token_sandbox) || is_null($nexus_endpoint) || is_null($nexus_endpoint_sandbox)) {
            throw new Exception('Missing Nexus configuration values');
        }

        $endpoint = $is_sandbox ? $nexus_endpoint_sandbox : $nexus_endpoint;
        $token = $is_sandbox ? $nexus_token_sandbox : $nexus_token;

        $response = Http::get($endpoint . "token", [
            "client_token" => $token
        ]);

        $responseObject = json_decode($response);

        if (isset($responseObject->error)) {
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

        // $campaign = Campaign::with('templates')
        //     ->where('days', 'like', '%' . $dayOfWeek . '%')
        //     ->where('is_active', 1)
        //     ->first();
        // dd($dayOfWeek);
        $campaign = Campaign::with('templates')
            ->where('days', 'like', '%' . $dayOfWeek . '%')
            ->where('is_active', 1)
            ->where('hour', $hourAndMinute)
            ->first();
        // dd($campaign);

        if (is_null($campaign)) {
            throw new Exception('No active campaign found for today');
        }

        $nexusConfig = getNexusConfig();

        if (isset($nexusConfig['error'])) {
            throw new Exception($nexusConfig['error']);
        }

        $token = $nexusConfig['token'];
        $endpoint = $nexusConfig['endpoint'];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->get($endpoint . $campaign->querydata->query);

        $collection = collect($response->json());

        if ($collection->isEmpty()) {
            throw new Exception('No data found from Nexus API');
        }

        $limitedCollection = $collection->take(10);

        $customResponse = $limitedCollection->map(function ($item) {
            return [
                'email' => $item['email'] ?? null,
                'msisdn' => $item['msisdn'] ?? null,
                'name' => $item['name'] ?? 'cliente'
            ];
        });

        foreach ($customResponse as $recipient) {
            Recipient::create([
                'name' => $recipient['name'],
                'campaign_id' => $campaign->id,
                'email' => $recipient['email'],
                'msisdn' => $recipient['msisdn']
            ]);
        }

        return response()->json($customResponse, 201);
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

function sendNotification()
{
    try {
        $recipients = Recipient::all()->take(2);
        $msisdnExamples = [
            '5542762991',
            '5632137142'
        ];

        foreach ($recipients as $key => $recipient) {
            if (isset($msisdnExamples[$key])) {
                $recipient->msisdn = $msisdnExamples[$key];
            }

            foreach ($recipient->campaign->templates as $template) {
                if ($template->channel->name == 'Email' && $recipient->email) {
                    echo sendEmail($recipient->email, $template->placeholder, $template->name, $recipient->name);
                }
                if ($template->channel->name == 'WhatsApp' && $recipient->msisdn) {
                    echo sendWhatsapp($recipient->msisdn, $template->placeholder);
                }
                if ($template->channel->name == 'SMS' && $recipient->msisdn) {
                    echo sendSms($recipient->msisdn, $template->placeholder);
                }
            }

            $recipient->delete();
        }

        return response()->json('Enviado', 201);
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

function sendEmail($to, $message, $campaignName, $name = 'unknown')
{
    try {
        Mail::to($to)->send(new Notification($name, $message, $campaignName));
        return 'Email sent successfully';
    } catch (Exception $e) {
        return 'Failed to send email: ' . $e->getMessage();
    }
}

function sendWhatsapp($msisdn, $message)
{
    try {
        $configuration = Configuration::whereIn('code', [
            'whatsapp_token',
            'id_phone_number_whatsapp',
            'api_whatsapp',
        ])->get();

        $configMap = $configuration->pluck('value', 'code');
        $whatsapp_token = $configMap['whatsapp_token'];
        $id_phone_number_whatsapp = $configMap['id_phone_number_whatsapp'];
        $api_whatsapp = $configMap['api_whatsapp'];

        $url = $api_whatsapp . $id_phone_number_whatsapp . '/messages';
        $client = new GuzzleHttp\Client();

        $response = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $whatsapp_token,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => '52' . $msisdn,
                'type' => 'template',
                'template' => [
                    'name' => 'hello_world',
                    'language' => [
                        'code' => 'en_US',
                    ],
                ],
            ],
        ]);

        return response()->json([
            'status' => 'success',
            'data' => json_decode($response->getBody(), true),
        ]);
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

function sendSms($msisdn, $message)
{

    if ($msisdn == '5542762991') {
        $msisdn = '5612377086';
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
            'Message' => $message,
            'PhoneNumber' => '+52' . $msisdn,
        ]);
        return $result;
    } catch (AwsException $e) {
        return $e->getMessage();
    }
}
