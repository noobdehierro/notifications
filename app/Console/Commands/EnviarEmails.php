<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EnviarEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:enviar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar emails de recordatorio a los usuarios';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $to = 'XlTn3@example.com';
            $subject = 'Asunto del correo del tipo Whatsapp';
            $body = 'Contenido del correo del tipo Whatsapp';

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
}
