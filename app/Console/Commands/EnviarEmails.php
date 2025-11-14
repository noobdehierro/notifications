<?php

namespace App\Console\Commands;

use App\Models\Recipient;
use Illuminate\Console\Command;

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
     * Control del bucle interno
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
        // --- LOCK PARA EVITAR EJECUCIONES EN PARALELO ------------------------
        if (! $this->acquireLock()) {
            $this->info("Ya hay un proceso ejecutándose. Saliendo...");
            return Command::SUCCESS;
        }

        $this->info("Iniciando proceso...");

        try {
            // ---------------------------------------------------------------------
            // PRIMERA CARGA DE DATOS
            // ---------------------------------------------------------------------
            getNexusResponse();

            // ---------------------------------------------------------------------
            // CICLO DE PROCESAMIENTO
            // ---------------------------------------------------------------------
            while ($this->shouldRun) {

                $this->info("Esperando 30 segundos antes del siguiente ciclo...");
                sleep(30);

                $processed = sendNotification(); // debe regresar true o false

                if (!$processed) {
                    $this->info('No hay más datos para procesar. Deteniendo el proceso.');
                    break;
                }
            }

            // ---------------------------------------------------------------------
            // ESTADO FINAL
            // ---------------------------------------------------------------------
            if (Recipient::count() > 0) {
                $this->info('Proceso finalizado con ' . Recipient::count() . ' datos restantes.');
                $this->shouldRun = true;
            } else {
                $this->info('Proceso finalizado sin datos restantes.');
                $this->shouldRun = false;
            }

            // Limpieza final
            \App\Models\RecipientCopy::truncate();

        } catch (\Exception $e) {

            // Manejo de errores
            $this->error('Error: ' . $e->getMessage());

        } finally {

            // --- LIBERA EL LOCK SIEMPRE, INCLUSO SI HAY ERRORES ---------------
            $this->releaseLock();
        }

        return Command::SUCCESS;
    }

    // =========================================================================
    // MÉTODOS DE LOCK
    // =========================================================================

    protected function acquireLock()
    {
        // Crea una llave que dura 10 minutos
        // cache()->add devuelve FALSE si ya existe → evita procesos paralelos
        return cache()->add('emails_enviar_lock', true, 600);
    }

    protected function releaseLock()
    {
        cache()->forget('emails_enviar_lock');
    }
}
