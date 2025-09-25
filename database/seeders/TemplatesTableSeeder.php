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
                'name' => 'Cambio De Parrilla',
                'placeholder' => '',
                'template_name' => 'cambio_de_parrilla',
            ],
            [
                'channel_id' => 3,
                'name' => 'Cambio De Parrilla',
                'placeholder' => '<div style="text-align: center; margin-bottom: 20px;">
    <h2 style="color: #e74c3c; font-size: 24px; margin-bottom: 20px;">¡Atención!</h2>
</div>

<p style="margin-bottom: 15px; font-size: 16px;">Hola, estimado usuario Figou.</p>

<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;">
    <h3 style="font-size: 20px; margin-bottom: 15px;">🚀 Figou sube de nivel 🚀</h3>
    <p style="margin-bottom: 10px; font-size: 16px;">Desde hoy tus recargas son mejores:</p>
    <ul style="text-align: left; display: inline-block; margin: 0 auto; list-style:none">
        <li style="margin-bottom: 8px;">✔️ Más GB en tus planes Figou</li>
        <li style="margin-bottom: 8px;">✔️ GB acumulables para tu siguiente recarga</li>
        <li style="margin-bottom: 8px;">✔️ Máxima velocidad siempre</li>
    </ul>
</div>

<p style="margin-bottom: 20px; font-size: 16px;">Revisa los nuevos planes en <a href="https://figou.mx/recargas" style="color: #667eea;">figou.mx/recargas</a> o en IgouPay 📲</p>

<p style="margin-bottom: 20px; font-size: 16px;">¿Preguntas? Te atendemos al <strong>5624962936</strong>.</p>

<div style="text-align: center; margin: 25px 0;">
    <a href="https://tinyurl.com/FIGOUU" class="button" style="display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 4px; font-weight: 600; margin: 15px 0;">
        Contáctanos por WhatsApp
    </a>
</div>

<div style="background-color: #f8f9fa; padding: 15px; border-radius: 6px; margin-top: 20px; font-size: 14px;">
    <p style="margin-bottom: 10px;">Si quieres saber más de promociones, beneficios e información de Figou</p>
    <p style="margin-bottom: 10px; font-weight: 600;">Sé parte de nuestro canal.</p>
    <p style="margin-bottom: 0;">© IGOU TELECOM S.A.P.I. de C.V.</p>
</div>'
            ],
            [
                'channel_id' => 1,
                'name' => 'Recordatorio De Recarga',
                'placeholder' => '',
                'template_name' => 'recordatorio_de_recarga',
            ],
            [
                'channel_id' => 3,
                'name' => 'Recordatorio De Recarga',
                'placeholder' => '<div style="text-align: center; margin-bottom: 20px;">
    <h2 style="color: #e74c3c; font-size: 24px; margin-bottom: 10px;">Recordatorio de recarga</h2>
</div>

<p style="margin-bottom: 15px; font-size: 16px;">Hola, estimado usuario Figou.</p>

<div style="background: linear-gradient(135deg, #ff9a44 0%, #fc6076 100%); color: white; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;">
    <p style="font-size: 18px; margin-bottom: 10px; font-weight: 600;">💡🚨 Tu recarga vence en 2 días. ¡No te quedes sin servicio! 💡🚨</p>
</div>

<p style="margin-bottom: 15px; font-size: 16px; font-weight: 600;">Recarga fácil en:</p>

<ul style="margin-bottom: 20px; padding-left: 20px; list-style: none;">
    <li style="margin-bottom: 10px; font-size: 16px;">👉 <a href="https://www.figou.mx/recargas" style="color: #667eea;">figou.mx/recargas</a></li>
    <li style="margin-bottom: 10px; font-size: 16px;">👉 Ó en tu App IgouPay 📲</li>
</ul>

<p style="margin-bottom: 20px; font-size: 16px;">¿Dudas? Escríbenos al <strong>5624962936</strong>.</p>

<div style="text-align: center; margin: 25px 0;">
    <a href="https://www.figou.mx/recargas" class="button" style="display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #ff9a44 0%, #fc6076 100%); color: white; text-decoration: none; border-radius: 4px; font-weight: 600; margin: 15px 0;">
        Recargar Ahora
    </a>
</div>

<div style="background-color: #f8f9fa; padding: 15px; border-radius: 6px; margin-top: 20px; font-size: 14px; text-align: center;">
    <p style="margin-bottom: 0;">© IGOU TELECOM S.A.P.I. de C.V.</p>
</div>'
            ]

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
