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
    $configuration = Configuration::whereIn('code', [
        'is_sandbox',
        'nexus_token',
        'nexus_token_sandbox',
        'nexus_endpoint',
        'nexus_endpoint_sandbox'
    ])->get();

    $is_sandbox = null;
    $nexus_token = null;
    $nexus_token_sandbox = null;
    $nexus_endpoint = null;
    $nexus_endpoint_sandbox = null;

    foreach ($configuration as $config) {
        switch ($config->code) {
            case 'is_sandbox':
                $is_sandbox = $config->value;
                break;
            case 'nexus_token':
                $nexus_token = $config->value;
                break;
            case 'nexus_token_sandbox':
                $nexus_token_sandbox = $config->value;
                break;
            case 'nexus_endpoint':
                $nexus_endpoint = $config->value;
                break;
            case 'nexus_endpoint_sandbox':
                $nexus_endpoint_sandbox = $config->value;
                break;
        }
    }

    if ($is_sandbox) {
        $endpoint = $nexus_endpoint_sandbox;
        $token = $nexus_token_sandbox;
    } else {
        $endpoint = $nexus_endpoint;
        $token = $nexus_token;
    }

    $response = Http::get($endpoint . "token", [
        "client_token" => $token
    ]);

    $responseObject = json_decode($response);

    return ([
        'endpoint' => $endpoint,
        'token' => $responseObject->data->access_token
    ]);
}

function getNexusResponse()
{
    $currentDateTime = Carbon::now();
    $hourAndMinute = $currentDateTime->format('H:i');
    $dayOfWeek = strtolower($currentDateTime->englishDayOfWeek);
    // $dayOfWeek = 'wednesday';

    $campaign = Campaign::with('templates')
        ->where('days', 'like', '%' . $dayOfWeek . '%')
        ->where('is_active', 1)
        ->first();

    $nexusConfig = getNexusConfig();

    $token = $nexusConfig['token'];
    $endpoint = $nexusConfig['endpoint'];

    // $response = Http::withHeaders([
    //     'Authorization' => 'Bearer ' . $token
    // ])->get($endpoint . $campaign->querydata->query);

    // return $response->json();

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token
    ])->get($endpoint . $campaign->querydata->query);

    // Convierte la respuesta JSON a una colección de Laravel
    $collection = collect($response->json());

    // Toma solo los primeros 5 elementos
    $limitedCollection = $collection->take(5);

    // return response()->json($limitedCollection);

    $customResponse = $limitedCollection->map(function ($item) {
        return [
            'email' => $item['email'] ?? null,
            'msisdn' => $item['msisdn'] ?? null,
            'name' => $item['name'] ?? 'cliente'
        ];
    });

    // return response()->json($customResponse);

    foreach ($customResponse as $recipient) {
        Recipient::create([
            'name' => $recipient['name'],
            'campaign_id' => $campaign->id,
            'email' => $recipient['email'],
            'msisdn' => $recipient['msisdn']
        ]);
    }

    return response()->json($customResponse, 201);
}


function sendNotification()
{

    $recipients = Recipient::all()->take(2);

    foreach ($recipients as $key => $recipient) {
        // echo $recipient->campaign->templates[$key];
        // echo $recipient->campaign->templates;
        foreach ($recipient->campaign->templates as $template) {
            // echo $template->channel;
            // if ($template->channel->name == 'WhatsApp') {
            //     if ($recipient->msisdn) {
            //         // echo 'whatsapp send to ' . $recipient->msisdn . ' ' . $template->title . ' ' . $template->body . PHP_EOL;
            //         echo sendWhatsapp($recipient->msisdn, $template->placeholder, $template->name, $recipient->name) . PHP_EOL;
            //     }
            // }
            // if ($template->channel->name == 'SMS') {
            //     if ($recipient->msisdn) {
            //         echo 'sms';
            //         echo sendSms($recipient->msisdn, $template->placeholder) . PHP_EOL;
            //     }
            // }
            // if ($template->channel->name == 'Email') {
            //     if ($recipient->email) {
            //         sendEmail($recipient->email, $template->placeholder, $template->name, $recipient->name);
            //     }
            // }
        }
    }

    return response()->json('Enviado', 201);
}

function sendWhatsapp($to, $message, $campaignName, $name = 'unknown')
{
    // dd($to, $message, $campaignName);
    // Mail::to('example@gmail.com')->send(new Notification());
    Mail::to($to . '@gmail.com')->send(new Notification($name, $message, $campaignName));
    return 'Whatsapp send to ' . $to . ' ' . $message . ' ' . $campaignName;
}

function sendSms($msisdn, $message)
{
    // dd($msisdn, $message);
    $SnSclient = new SnsClient([
        'region' => env('AWS_DEFAULT_REGION'),
        'version' => '2010-03-31',
        'credentials' => [
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
        ]
    ]);

    $message = $message;
    // $phone = '5522439861';
    $phone = $msisdn;
    try {
        $result = $SnSclient->publish([
            'Message' => $message,
            'PhoneNumber' => '+52' . $phone,
        ]);
        return $result;
    } catch (AwsException $e) {
        return $e->getMessage();
    }
}

function sendEmail($to, $message, $campaignName, $name = 'unknown')
{
    $response = Mail::to($to)->send(new Notification($name, $message, $campaignName));

    return $response;
}
