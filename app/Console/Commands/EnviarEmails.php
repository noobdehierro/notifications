<?php

namespace App\Console\Commands;

use App\Models\Campaign;
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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        getNexusResponse();
        sendNotification();
    }
}
