<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Holiday;
use App\Calendar;
use Carbon\Carbon;

use function PHPUnit\Framework\isEmpty;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Per visualizzare il calendario
        $calendar = new Calendar();

        // Ordino gli eventi cronologicamente per visualizzarli in ordine nel popup
        $holidays = DB::table('holidays')
            ->orderBy('data', 'ASC')
            ->get();

        // Creare variabile data giorno prima di ieri
        $ieri = Carbon::yesterday()->subDay();

        $holidays_show = [];

        foreach ($holidays as $holiday) {
            // Mi costruisco il formato Carbon per fare le operazioni
            $data_carbon = Carbon::parse($holiday->data);

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

        // Come scritto sulla traccia visualizzare gli eventi in ordine di creazione
        $holidays = DB::table('holidays')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('index', compact('holidays', 'holidays_show', 'calendar'));
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
        $giorno = $data->day;
        $mese = $data->month;
        if ($data->month == 1) $mese = 'Gennaio';
        elseif ($data->month == 2) $mese = 'Febbraio';
        elseif ($data->month == 3) $mese = 'Marzo';
        elseif ($data->month == 4) $mese = 'Aprile';
        elseif ($data->month == 5) $mese = 'Maggio';
        elseif ($data->month == 6) $mese = 'Giugno';
        elseif ($data->month == 7) $mese = 'Luglio';
        elseif ($data->month == 8) $mese = 'Agosto';
        elseif ($data->month == 9) $mese = 'Settembre';
        elseif ($data->month == 10) $mese = 'Ottobre';
        elseif ($data->month == 11) $mese = 'Novembre';
        elseif ($data->month == 12) $mese = 'Dicembre';
        $anno = $data->year;
        $new_holiday = new Holiday();
        $new_holiday->data = $data;
        $new_holiday->descrizione = $form_data['descrizione'];

        // Se non é settato ogni anno dare valore 2(no) 
        if (isset($form_data['ogni_anno'])) {
            if ($form_data['ogni_anno'] === 1) {
                $new_holiday->ogni_anno = 1;
            } else {
                $new_holiday->ogni_anno = 2;
            }
        } else {
            $new_holiday->ogni_anno = 2;
        }

        $new_holiday->created_at = Carbon::now()->toDateTimeString();
        $new_holiday->updated_at = Carbon::now()->toDateTimeString();

        $new_holiday->save();

        $send = array(
            'data' => $giorno . ' ' . $mese . '' . $anno,
            'descrizione' => $form_data['descrizione'],
            'ogni_anno' => $new_holiday->ogni_anno == 1 ? 'Si' : 'No',
        );

        return response()->json($send);
    }

    // Filtrare le festivitá
    public function filter(Request $request)
    {
        // Per visualizzare il calendario
        $calendar = new Calendar();

        // Risultato predefinito dei filtri
        $holidays = DB::table('holidays')
            ->get();

        // Se vengono definite le date
        if (!$request->start_date == NULL) {
            $start = Carbon::parse($request->start_date);
        }
        if (!$request->end_date == NULL) {
            $end = Carbon::parse($request->end_date);
        }


        $evento = $request->descrizione;

        // Se viene definito l'evento
        if (!$evento == NULL) {
            // Se gli passo l'opzione cerca negli anni
            if ($request->perAnni == 'si') {
                if (!$request->start_date == NULL && !$request->end_date == NULL) {
                    $holidays = DB::table('holidays')
                        ->whereBetween('data', [$start, $end])
                        ->where('descrizione', $evento)
                        ->get();
                    $holidaysAnni[] = $holidays;
                } else {
                    $holidays = DB::table('holidays')
                        ->where('descrizione', $evento)
                        ->where('descrizione', 'like', $evento)
                        ->get();
                }
            } else {
                if (!$request->start_date == NULL && !$request->end_date == NULL) {
                    $holidays = DB::table('holidays')
                        ->whereBetween('data', [$start, $end])
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
                // Cicla per ogni anno presente nel database
                if (!$request->start_date == NULL && !$request->end_date == NULL) {
                    $holidays = DB::table('holidays')
                        ->whereBetween('data', [$start, $end])
                        ->get();
                    $holidaysAnni[] = $holidays;
                }
            } else {
                if (!$request->start_date == NULL && !$request->end_date == NULL) {
                    $holidays = DB::table('holidays')
                        ->whereBetween('data', [$start, $end])
                        ->whereBetween('anno', [$annoIniziale, $annoFinale])      //C'é questa condizione in piú
                        ->get();
                    $holidaysAnni[] = $holidays;
                }
            }
        }

        if (isset($holidaysAnni)) {
            return view('index', compact('holidays', 'holidaysAnni', 'calendar'));
        }
        return view('index', compact('holidays'));
    }

    // Ordinare le festivitá
    public function order(Request $request)
    {
        // Per visualizzare il calendario
        $calendar = new Calendar();

        $ordinamento = $request->ordine;
        if ($ordinamento == 1) {
            $holidays = DB::table('holidays')
                ->orderBy('created_at', 'DESC')
                ->get();
        } else {
            $holidays = DB::table('holidays')
                ->orderBy('data', 'ASC')
                ->get();
        }

        return view('index', compact('holidays', 'calendar'));
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
        // Per visualizzare il calendario
        $calendar = new Calendar();

        return view('holidays.edit', compact('holiday', 'calendar'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        //
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
