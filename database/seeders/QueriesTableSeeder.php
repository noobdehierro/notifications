<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QueriesTableSeeder extends Seeder
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

        $queries = [
            [
                'name' => 'proximos a suspender',
                'query' => 'queries/before_suspention',
            ],
            [
                'name' => 'portabilidad fallida',
                'query' => 'queries/failed_portabilities',
            ]

        ];

        foreach ($queries as $query) {
            DB::table('queries')->insert([
                'name' => $query['name'],
                'query' => $query['query'],
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ]);
        }
    }
}
