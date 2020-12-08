<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Holiday;
use Carbon\Carbon;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $holidays = DB::table('holidays')
            ->orderBy('created_at', 'DESC')
            ->get();

        // Creare variabile data giorno prima di ieri
        $ieri = Carbon::yesterday()->subDay();

        $holidays_show = [];

        foreach ($holidays as $holiday) {
            // Mi costruisco il formato Carbon per fare le operazioni
            $data = $holiday->giorno . "-" . $holiday->mese . "-" . $holiday->anno;
            $data_carbon = Carbon::parse($data);

            // Se la data dell'evento é piu avanti della data dell'altro ieri
            if ($data_carbon->greaterThan($ieri)) {
                // Se é presente un evento alla data di ieri
                if ($ieri->diffInDays($data_carbon) == 1) {
                    $holidays_show['ieri'] = $holiday;
                }
                // Se é presente un evento alla data di oggi
                else if ($ieri->diffInDays($data_carbon) == 2) {
                    $holidays_show['oggi'] = $holiday;
                }
                // Se é presente un evento nei successivi 31 giorni
                else if ($ieri->diffInDays($data_carbon) >= 3 && $ieri->diffInDays($data_carbon) <= 31) {
                    $holidays_show[] = $holiday;
                }
            }
        }
        // sort($holidays_show);


        return view('index', compact('holidays', 'holidays_show'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'data' => 'required',
            'descrizione' => 'required',
        ]);

        $form_data = $request->all();
        $data = Carbon::parse($form_data['data']);
        $new_holiday = new Holiday();
        $new_holiday->giorno = $data->day;
        $new_holiday->mese = $data->month;
        $new_holiday->anno = $data->year;
        $new_holiday->descrizione = $form_data['descrizione'];
        $new_holiday->ogni_anno = $form_data['ogni_anno'];
        $new_holiday->created_at = Carbon::now()->toDateTimeString();
        $new_holiday->updated_at = Carbon::now()->toDateTimeString();

        $new_holiday->save();

        return redirect()->route('holidays.index', $new_holiday);
    }

    // Filtrare le festivitá
    public function filter(Request $request)
    {
        // Risultato predefinito dei filtri
        $holidays = DB::table('holidays')
            ->get();

        // Se vengono definite le date
        if (!$request->start_date == NULL) {
            $start = Carbon::parse($request->start_date);
            $giornoIniziale = $start->day;
            $meseIniziale = $start->month;
            $annoIniziale = $start->year;
        }
        if (!$request->end_date == NULL) {
            $end = Carbon::parse($request->end_date);
            $giornoFinale = $end->day;
            $meseFinale = $end->month;
            $annoFinale = $end->year;
        }


        $evento = $request->descrizione;

        // Se viene definito l'evento
        if (!$evento == NULL) {
            // Se gli passo l'opzione cerca negli anni
            if ($request->perAnni == 'si') {
                // dd('ciao');
                // Cicla per ogni anno presente nel database
                if (!$request->start_date == NULL && !$request->end_date == NULL) {
                    $holidays = DB::table('holidays')
                        ->whereBetween('giorno', [$giornoIniziale, $giornoFinale])
                        ->whereBetween('mese', [$meseIniziale, $meseFinale])
                        ->where('descrizione', $evento)
                        ->get();
                    $holidaysAnni[] = $holidays;
                } else {
                    $holidays = DB::table('holidays')
                        ->where('descrizione', $evento)
                        ->get();
                }
            } else {
                // dd('ciao2');
                if (!$request->start_date == NULL && !$request->end_date == NULL) {
                    $holidays = DB::table('holidays')
                        ->whereBetween('giorno', [$giornoIniziale, $giornoFinale])
                        ->whereBetween('mese', [$meseIniziale, $meseFinale])
                        ->whereBetween('anno', [$annoIniziale, $annoFinale])      //C'é questa condizione in piú
                        ->where('descrizione', $evento)
                        ->get();
                    $holidaysAnni[] = $holidays;
                } else {
                    $holidays = DB::table('holidays')
                        ->where('descrizione', $evento)
                        ->get();
                }
            }
        }
        // Se non viene definito l'evento
        else {
            // Se gli passo l'opzione cerca negli anni
            if ($request->perAnni == 'si') {
                // dd('ciao3');
                // Cicla per ogni anno presente nel database
                if (!$request->start_date == NULL && !$request->end_date == NULL) {
                    $holidays = DB::table('holidays')
                        ->whereBetween('giorno', [$giornoIniziale, $giornoFinale])
                        ->whereBetween('mese', [$meseIniziale, $meseFinale])
                        ->get();
                    $holidaysAnni[] = $holidays;
                }
            } else {
                // dd('ciao4');
                if (!$request->start_date == NULL && !$request->end_date == NULL) {
                    $holidays = DB::table('holidays')
                        ->whereBetween('giorno', [$giornoIniziale, $giornoFinale])
                        ->whereBetween('mese', [$meseIniziale, $meseFinale])
                        ->whereBetween('anno', [$annoIniziale, $annoFinale])      //C'é questa condizione in piú
                        ->get();
                    $holidaysAnni[] = $holidays;
                }
            }
        }

        if (isset($holidaysAnni)) {
            return view('index', compact('holidays', 'holidaysAnni'));
        }
        return view('index', compact('holidays'));
    }

    // Ordinare le festivitá
    public function order(Request $request)
    {
        $ordinamento = $request->ordine;
        if ($ordinamento == 1) {
            $holidays = DB::table('holidays')
                ->orderBy('created_at', 'DESC')
                ->get();
        } else {
            $holidays = DB::table('holidays')
                ->orderBy('anno', 'ASC')
                ->orderBy('mese', 'ASC')
                ->orderBy('giorno', 'ASC')
                ->get();
        }

        return view('index', compact('holidays'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Holiday $holiday)
    {
        return view('holidays.edit', compact('holiday'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'data' => 'required',
            'descrizione' => 'required',
        ]);

        $form_data = $request->all();
        $data = Carbon::parse($form_data['data']);
        $holiday->giorno = $data->day;
        $holiday->mese = $data->month;
        $holiday->anno = $data->year;
        $holiday->descrizione = $form_data['descrizione'];
        $holiday->ogni_anno = $form_data['ogni_anno'];
        $holiday->updated_at = Carbon::now()->toDateTimeString();

        $holiday->update();

        return redirect()->route('holidays.index', $holiday);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return redirect()->route('holidays.index');
    }
}
