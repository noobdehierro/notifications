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

        // Log::info('Proceso iniciado');


        getNexusResponse();

        while ($this->shouldRun) {
            // Log::info('dentro del while');
            try {




                $processed = sendNotification();
                
                if (!$processed) {
                    $this->info('No hay más datos para procesar. Deteniendo el proceso.');
                    Log::info('No hay más datos para procesar. Deteniendo el proceso.');
                    break; // Salir del ciclo
                }

                sleep(10); // Esperar antes de la siguiente iteración
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