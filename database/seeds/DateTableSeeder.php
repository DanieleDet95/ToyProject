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
                'giorno' => '01',
                'mese' => '01',
                'anno' => '2020',
                'descrizione' => 'Capodanno',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'giorno' => '07',
                'mese' => '01',
                'anno' => '2020',
                'descrizione' => 'Epifania',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'giorno' => '01',
                'mese' => '04',
                'anno' => '2020',
                'descrizione' => 'Pasqua',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'giorno' => '02',
                'mese' => '04',
                'anno' => '2020',
                'descrizione' => 'Pasquetta',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'giorno' => '25',
                'mese' => '04',
                'anno' => '2020',
                'descrizione' => 'Festa della liberazione',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'giorno' => '01',
                'mese' => '05',
                'anno' => '2020',
                'descrizione' => 'Festa dei lavoratori',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'giorno' => '02',
                'mese' => '06',
                'anno' => '2020',
                'descrizione' => 'Festa della repubblica',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'giorno' => '15',
                'mese' => '08',
                'anno' => '2020',
                'descrizione' => 'Ferragosto',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'giorno' => '01',
                'mese' => '11',
                'anno' => '2020',
                'descrizione' => 'Tutti i santi',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'giorno' => '08',
                'mese' => '12',
                'anno' => '2020',
                'descrizione' => 'Immacolata',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'giorno' => '10',
                'mese' => '12',
                'anno' => '2020',
                'descrizione' => 'Controllo del progetto',
                'ogni_anno' => false,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'giorno' => '25',
                'mese' => '12',
                'anno' => '2020',
                'descrizione' => 'Natale',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'giorno' => '26',
                'mese' => '12',
                'anno' => '2020',
                'descrizione' => 'Santo Stefano',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ], [
                'giorno' => '31',
                'mese' => '12',
                'anno' => '2020',
                'descrizione' => 'San Silvestro',
                'ogni_anno' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]
        ]);
    }
}
