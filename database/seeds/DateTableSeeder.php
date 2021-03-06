<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('holidays')->insert([
            [
                'data' => '2021-01-01',
                'descrizione' => 'Capodanno',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],  [
                'data' => '1861-03-18',
                'descrizione' => "Unitá d'italia",
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2021-04-01',
                'descrizione' => 'Pasqua',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2021-04-25',
                'descrizione' => 'Festa della liberazione',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2021-05-01',
                'descrizione' => 'Festa dei lavoratori',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2021-06-02',
                'descrizione' => 'Festa della repubblica',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2021-08-15',
                'descrizione' => 'Ferragosto',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '1989-11-09',
                'descrizione' => 'Caduta muro di berlino',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2021-12-25',
                'descrizione' => 'Natale',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2021-12-26',
                'descrizione' => 'Santo Stefano',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]
        ]);
    }
}
