<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\Recipient;
use Carbon\Carbon;
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

        getNexusResponse();

        while ($this->shouldRun) {
            try {

                $this->info("Esperando 30 segundos antes del siguiente ciclo...");
                sleep(30);

                $processed = sendNotification();

                if (!$processed) {
                    $this->info('No hay más datos para procesar. Deteniendo el proceso.');
                    break;
                }
            } catch (\Exception $e) {
                $this->error('Error: ' . $e->getMessage());
            }
        }


        if (Recipient::count() > 0) {
            $this->info('Proceso finalizado con ' . Recipient::count() . ' datos restantes.');
            $this->shouldRun = true;
        } else {
            $this->info('Proceso finalizado.');
            $this->shouldRun = false;
        }

        \App\Models\RecipientCopy::truncate();
    }
}
