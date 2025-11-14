<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\Recipient;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
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
     * Interruptor para detener o reiniciar el proceso.
     *
     * @var bool
     */
    protected $shouldRun = true;


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 🔒 Lock para evitar procesos simultáneos
        if (! cache()->add('emails_enviar_lock', true, 120)) {
            $this->info('Otro proceso ya está corriendo.');
            Log::info('Otro proceso ya estaba corriendo.');
            return 0;
        }
        getNexusResponse();

        try {

            while (true) {
                try {
                    $processed = sendNotification();

                    if (!$processed) {
                        $this->info('No hay más datos para procesar. Deteniendo el proceso.');
                        Log::info('No hay más datos para procesar. Deteniendo el proceso.');
                        break;
                    }

                    sleep(10);
                } catch (\Exception $e) {
                    $this->error('Error: ' . $e->getMessage());
                    Log::error('Error: ' . $e->getMessage());
                }
            }
        } finally {
            // 🔓 Liberar lock
            cache()->forget('emails_enviar_lock');
        }

        $this->info('Proceso finalizado.');
        Log::info('Proceso finalizado.');
        \App\Models\RecipientCopy::truncate();
    }
}
