<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dt = Carbon::now();
        $dateNow = $dt->toDateTimeString();

        $templates = [
            [
                'channel_id' => 1,
                'name' => 'Lanzamineto didi food',
                'placeholder' => '',
                'template_name' => 'lanzamiento_food',
            ],
            [
                'channel_id' => 2,
                'name' => 'se aproxima suspensión de linea telefónica',
                'placeholder' => '¡Hola! Tu línea telefónica ha sido suspendida por falta de pago. Contacta a tu proveedor para resolverlo cuanto antes. Lamentamos los inconvenientes, saludos.',
            ],
            [
                'channel_id' => 3,
                'name' => 'se aproxima suspensión de linea telefónica',
                'placeholder' => '<h1>Suspensión Temporal del Servicio Telefónico</h1>
<p>Estimado cliente,</p>
<p>Nos dirigimos a usted para informarle que su servicio telefónico ha sido suspendido temporalmente debido a una actualización en nuestra infraestructura. Esta medida es parte de nuestros esfuerzos continuos por mejorar la calidad y fiabilidad de nuestros servicios.</p>
<p>Estamos trabajando diligentemente para completar esta actualización lo antes posible y restaurar su servicio telefónico. Lamentamos cualquier inconveniente que esto pueda causarle y agradecemos su comprensión durante este tiempo.</p>
<p>Para obtener más información o asistencia inmediata, por favor no dude en contactar a nuestro equipo de soporte técnico disponible las 24 horas.</p>',
            ],
            [
                'channel_id' => 2,
                'name' => 'su portabilidad a fallado',
                'placeholder' => 'La portabilidad de su número falló por problemas técnicos. Contacte a atención al cliente para asistencia. Gracias por su paciencia mientras resolvemos.',
            ],
            [
                'channel_id' => 3,
                'name' => 'su portabilidad a fallado',
                'placeholder' => '<h1>Portabilidad Telefónica Fallida</h1>
<p>Estimado cliente,</p>
<p>Le informamos que el proceso de portabilidad de su número telefónico ha fallado debido a un problema técnico imprevisto. Estamos trabajando para resolver este inconveniente lo antes posible.</p>
<p>Por favor, contacte a nuestro servicio de atención al cliente para obtener más información y asistencia. Agradecemos su paciencia y comprensión mientras solucionamos esta situación.</p>
<p>Gracias por su confianza en nuestros servicios.</p>',
            ],
        ];

        foreach ($templates as $template) {
            DB::table('templates')->insert([
                'channel_id' => $template['channel_id'],
                'name' => $template['name'],
                'placeholder' => $template['placeholder'],
                'template_name' => $template['template_name'] ?? null,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ]);
        }
    }
}
