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
            ]
            // , [
            //     'date' => '2020-04-25',
            //     'name' => 'Festa della liberazione',
            //     'ogni_anno' => true
            // ], [
            //     'date' => '2020-05-01',
            //     'name' => 'Festa dei lavoratori',
            //     'ogni_anno' => true
            // ], [
            //     'date' => '2020-06-02',
            //     'name' => 'Festa della repubblica',
            //     'ogni_anno' => true
            // ], [
            //     'date' => '2020-08-15',
            //     'name' => 'Ferragosto',
            //     'ogni_anno' => true
            // ], [
            //     'date' => '2020-11-01',
            //     'name' => 'Tutti i santi',
            //     'ogni_anno' => true
            // ], [
            //     'date' => '2020-12-08',
            //     'name' => 'Immacolata',
            //     'ogni_anno' => true
            // ], [
            //     'date' => '2020-12-25',
            //     'name' => 'Natale',
            //     'ogni_anno' => true
            // ], [
            //     'date' => '2020-12-26',
            //     'name' => 'Santo Stefano',
            //     'ogni_anno' => true
            // ], [
            //     'date' => '2020-12-31',
            //     'name' => 'San Silvestro',
            //     'ogni_anno' => true
            // ]
        ]);
    }
}
