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
                'data' => '2020-01-01',
                'descrizione' => 'Capodanno',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2020-01-07',
                'descrizione' => 'Epifania',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2020-04-01',
                'descrizione' => 'Pasqua',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2020-04-02',
                'descrizione' => 'Pasquetta',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2020-04-25',
                'descrizione' => 'Festa della liberazione',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2020-05-01',
                'descrizione' => 'Festa dei lavoratori',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2020-06-02',
                'descrizione' => 'Festa della repubblica',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2020-08-15',
                'descrizione' => 'Ferragosto',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2020-11-01',
                'descrizione' => 'Tutti i santi',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2020-12-08',
                'descrizione' => 'Immacolata',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2020-12-14',
                'descrizione' => 'Controllo del progetto',
                'ogni_anno' => false,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2020-12-25',
                'descrizione' => 'Natale',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2020-12-26',
                'descrizione' => 'Santo Stefano',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'data' => '2020-12-31',
                'descrizione' => 'San Silvestro',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]
        ]);
    }
}
