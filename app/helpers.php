<?php

use App\Mail\Notification;
use App\Models\Campaign;
use App\Models\Configuration;
use App\Models\Recipient;
use App\Models\RecipientCopy;
use Aws\Exception\AwsException;
use Aws\Sns\SnsClient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

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
    try {
        $recipients = Recipient::orderBy('id')->limit(1)->get();


        if ($recipients->isEmpty()) {
            return false;
        }

        foreach ($recipients as $recipient) {
            // 1. Guardar datos antes de eliminar
            $recipientData = [
                'campaign_id' => $recipient->campaign_id,
                'email'       => $recipient->email,
                'msisdn'      => $recipient->msisdn,
                'name'        => $recipient->name,
                'campaign'    => $recipient->campaign,
            ];

            // 2. Eliminar el registro antes de procesar
            $recipient->delete();

            // 3. Procesar con la data guardada
            $campaignId = $recipientData['campaign_id'];
            $templates  = $recipientData['campaign']->templates;

            foreach ($templates as $template) {
                $channelName = $template->channel->name;
                $placeholder = $template->placeholder;
                $recipientName = $recipient->name;

                // === EMAIL ===
                if ($channelName == 'Email' && $recipient->email) {
                    $exists = RecipientCopy::where('campaign_id', $campaignId)
                        ->where('email', $recipient->email)
                        ->exists();

                    if (!$exists) {
                        $responseSendEmail = sendEmail(
                            $recipient->email,
                            $placeholder,
                            $template->name,
                            $recipientName
                        );
                        // Guardar registro en RecipientCopy
                        RecipientCopy::create([
                            'campaign_id' => $campaignId,
                            'email' => $recipient->email,
                            'msisdn' => $recipient->msisdn,
                        ]);

                        Recipient::where('campaign_id', $campaignId)
                            ->where('email', $recipient->email)
                            ->update(['email_sent' => true]);
                    } else {
                        Log::info("Email ya enviado a {$recipient->email} para campaña {$campaignId}");
                    }
                }

                // === WHATSAPP ===
                if ($channelName == 'WhatsApp' && $recipient->msisdn) {
                    $exists = RecipientCopy::where('campaign_id', $campaignId)
                        ->where('msisdn', $recipient->msisdn)
                        ->exists();

                    if (!$exists) {
                        $imgUrl = $template->url_image ?? null;
                        sendWhatsapp($recipient->msisdn, $template->template_name, $imgUrl);
                        RecipientCopy::create([
                            'campaign_id' => $campaignId,
                            'email' => $recipient->email,
                            'msisdn' => $recipient->msisdn,
                        ]);
                    } else {
                        Log::info("WhatsApp ya enviado a {$recipient->msisdn} para campaña {$campaignId}");
                    }
                }

                // === SMS === (opcional mantener igual)
                if ($channelName == 'SMS' && $recipient->msisdn) {
                    $exists = RecipientCopy::where('campaign_id', $campaignId)
                        ->where('msisdn', $recipient->msisdn)
                        ->exists();

                    if (!$exists) {
                        sendSms($recipient->msisdn, $placeholder);

                        RecipientCopy::create([
                            'campaign_id' => $campaignId,
                            'email' => $recipient->email,
                            'msisdn' => $recipient->msisdn,
                        ]);
                    } else {
                        Log::info("SMS ya enviado a {$recipient->msisdn} para campaña {$campaignId}");
                    }
                }
            }

            // Eliminar el recipient original después de procesar
            $recipient->delete();
        }

        return true;
    } catch (Exception $e) {
        Log::error('Error in sendNotification: ' . $e->getMessage());
        return false;
    }
}


function sendEmail($to, $message, $campaignName, $name = 'unknown')
{
    try {
        $response = Mail::to($to)->send(new Notification($name, $message, $campaignName));
        Log::info("📧 Simulación de envío: To={$to}, Subject=mamacitas puebla, Name={$name}, Campaign Name={$campaignName}");
        return true; // Simula éxito       
        // dd($response);
    } catch (Exception $e) {
        // return 'Failed to send email: ' . $e->getMessage();
        // dd($e);
        Log::error('Error in sendEmail: ' . $e->getMessage());
        return false;
    }
}

function sendWhatsapp($msisdn, $template_name, $img_url = null)
{
    try {
        $configKeys = [
            'whatsapp_token',
            'id_phone_number_whatsapp',
            'api_whatsapp',
        ];
        $configurations = Configuration::whereIn('code', $configKeys)->pluck('value', 'code');

        $client = new Client();

        // Construir el array base del template
        $templateData = [
            "name" => $template_name,
            "language" => [
                "code" => "es_MX"
            ]
        ];

        // Agregar componentes solo si hay imagen
        if ($img_url !== null) {
            $templateData["components"] = [
                [
                    "type" => "header",
                    "parameters" => [
                        [
                            "type" => "image",
                            "image" => [
                                "link" => $img_url
                            ]
                        ]
                    ]
                ]
            ];
        }

        $response = $client->post($configurations['api_whatsapp'] . $configurations['id_phone_number_whatsapp'] . '/messages', [
            'headers' => [
                'Authorization' => 'Bearer ' . $configurations['whatsapp_token'],
                'Content-Type' => 'application/json',
            ],
            'json' => [
                "messaging_product" => "whatsapp",
                "to" => "52" . $msisdn,
                "type" => "template",
                "template" => $templateData
            ]
        ]);

        Log::info("📧 Simulación de envío: To={$msisdn}, Template Name={$template_name}, Image URL={$img_url}");

        return response()->json([
            'status' => 'success',
            'data' => json_decode($response->getBody(), true),
        ]);
    } catch (Exception $e) {
        // agregar un log de error
        Log::error('Error sending WhatsApp message: ' . $e->getMessage());

        return response()->json(['error' => $e->getMessage()], 500);
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
