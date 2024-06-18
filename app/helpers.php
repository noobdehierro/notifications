<?php

use App\Models\Campaign;
use App\Models\Configuration;
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

    // return $response->json();

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

    // Inicializa dos arrays para almacenar los emails y los msisdn
    $emails = [];
    $msisdns = [];

    // Itera sobre los elementos limitados y separa los emails y los msisdns
    $limitedCollection->each(function ($item) use (&$emails, &$msisdns) {
        if (isset($item['email'])) {
            $emails[] = $item['email'];
        }
        if (isset($item['msisdn'])) {
            $msisdns[] = $item['msisdn'];
        }
    });



    foreach ($campaign->templates as $template) {
        if ($template->channel->name == 'SMS') {
            echo "Enviando SMS";

            if (count($msisdns) > 0) {
                sendSms($msisdns, $template->placeholder);
            }
        }

        if ($template->channel->name == 'Email') {
            echo "Enviando Email";

            if (count($emails) > 0) {
                sendEmail($emails, $template->placeholder);
            }
        }

        if ($template->channel->name == 'WhatsApp') {
            echo "Enviando Whatsapp";

            if (count($emails) > 0) {
                sendWhatsapp($emails, $template->placeholder);
            }
        }
    }
    dd($emails, $msisdns);
    // if (count($emails) > 0) {
    //     sendEmail($emails, $campaign->templates->placeholder);
    // }
}

function sendSms($to, $message)
{
    try {
        $to = $to;
        $subject = 'Asunto del correo del tipo SMS';
        $body = $message;

        Mail::html($body, function ($message) use ($to, $subject) {
            $message->to($to)
                ->subject($subject);
        });

        // Log::info("Correo enviado correctamente a $to");
        return true;
    } catch (\Exception $e) {
        // Log::error("Error al enviar el correo: " . $e->getMessage());
        return false;
    }
}

function sendEmail($to, $message)
{
    try {
        $to = $to;
        $subject = 'Asunto del correo del tipo Email';
        $body = $message;

        Mail::html($body, function ($message) use ($to, $subject) {
            $message->to($to)
                ->subject($subject);
        });

        // Log::info("Correo enviado correctamente a $to");
        return true;
    } catch (\Exception $e) {
        // Log::error("Error al enviar el correo: " . $e->getMessage());
        return false;
    }
}

function sendWhatsapp($to, $message)
{
    try {
        $to = $to;
        $subject = 'Asunto del correo del tipo Whatsapp';
        $body = $message;

        Mail::html($body, function ($message) use ($to, $subject) {
            $message->to($to)
                ->subject($subject);
        });

        // Log::info("Correo enviado correctamente a $to");
        return true;
    } catch (\Exception $e) {
        // Log::error("Error al enviar el correo: " . $e->getMessage());
        return false;
    }
}
