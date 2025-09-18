<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChannelsTableSeeder extends Seeder
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

        $channels = [
            [
                'name' => 'WhatsApp',
                'max_characters' => 320,
            ],

            [
                'name' => 'SMS',
                'max_characters' => 160,
            ],

            [
                'name' => 'Email',
                'max_characters' => 10000,
            ],

        ];

        foreach ($channels as $channel) {
            DB::table('channels')->insert([
                'name' => $channel['name'],
                'max_characters' => $channel['max_characters'],
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ]);
        }
    }
}
