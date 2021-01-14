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

        // Creare variabile del giorno da confrontare
        $oggi = Carbon::today();
        $giorno = $oggi->day;
        $mese = $oggi->month;
        $holidays_show = [];

        foreach ($holidays as $holiday) {

            // Se é un evento che si ripete negli anni
            if ($holiday->ogni_anno == 1) {
                // Mi costruisco il formato Carbon per fare le operazioni
                $data_carbon = Carbon::parse($holiday->data);
                $giornoHoliday = $data_carbon->day;
                $meseHoliday = $data_carbon->month;

                // Se il mese é lo stesso
                if ($meseHoliday == $mese) {

                    // Se la data dell'evento é piu avanti della data di ieri
                    if ($giornoHoliday >= ($giorno - 1)) {
                        // Se é presente un evento alla data di ieri
                        if (($giorno - $giornoHoliday) == 1) {
                            $holidays_show['ieri'] = $holiday;
                        }
                        // Se é presente un evento alla data di oggi
                        else if (($giorno - $giornoHoliday) == 0) {
                            $holidays_show['oggi'] = $holiday;
                        }
                        // Se é presente un evento nei successivi 31 giorni
                        else if ((($giornoHoliday - $giorno) >= 3) && (($giornoHoliday - $giorno) <= 31)) {
                            $holidays_show[] = $holiday;
                        }
                    }
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
        $id = $form_data['id'];

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

        // Se é la creazione di un nuovo evento
        if ($id === null) {

            $new_holiday = new Holiday();
            $new_holiday->data = $data;

            $new_holiday->descrizione = $form_data['descrizione'];

            // Se non é settato ogni anno dare valore 2(no) 
            if (isset($form_data['ogni_anno'])) {
                if ($form_data['ogni_anno'] == 1) {
                    $new_holiday->ogni_anno = 1;
                } else {
                    $new_holiday->ogni_anno = 2;
                }
            } else {
                $new_holiday->ogni_anno = 2;
            }

            $new_holiday->save();

            // Data da passare a data.copia per la modifica 
            $copia = $anno . '/' . $data->month . '/' . $giorno;
            $id = $new_holiday->id;
            $data = $giorno . " " . $mese . " " . $anno;

            $send = array(
                'id' => $id,
                'data' => $data,
                'copia' => $copia,
                'descrizione' => $form_data['descrizione'],
                'ogni_anno' => isset($form_data['ogni_anno']) == 1 ? 'Si' : 'No',
            );
        } else {

            Holiday::where('id', $id)
                ->update([
                    'data' => $form_data['data'],
                    'descrizione' => $form_data['descrizione'],
                    'ogni_anno' => $form_data['ogni_anno'],
                ]);

            // Data da passare a data.copia per la modifica 
            $copia = $anno . '/' . $data->month . '/' . $giorno;

            $data = $giorno . " " . $mese . " " . $anno;

            $send = array(
                'id' => $id,
                'data' => $data,
                'copia' => $copia,
                'descrizione' => $form_data['descrizione'],
                'ogni_anno' => isset($form_data['ogni_anno']) == 1 ? 'Si' : 'No',
            );
        }

        return response()->json($send);
    }

    // Filtrare le festivitá
    public function filter(Request $request)
    {
        // Per visualizzare il calendario
        $calendar = new Calendar();

        // Inizializzazione query
        $query = DB::table('holidays');

        // Se vengono definite le date
        if (!$request->start_date == NULL) {
            $start = Carbon::parse($request->start_date);
            $start_giorno = $start->day;
            $start_mese = $start->month;
        }
        if (!$request->end_date == NULL) {
            $end = Carbon::parse($request->end_date);
            $end_giorno = $end->day;
            $end_mese = $end->month;
        }

        $evento = $request->filtro;

        // Se ricerco per anni e definisco le date
        if ($request->perAnni == 'si') {
            if (!$request->start_date == NULL && !$request->end_date == NULL) {
                $query
                    ->whereBetween(DB::raw('DAY(data)'), [$start_giorno, $end_giorno])
                    ->whereBetween(DB::raw('MONTH(data)'), [$start_mese, $end_mese]);
            } // Se non definisco le date
        } // Se non ricerco per anni e definisco le date
        else {
            if (!$request->start_date == NULL && !$request->end_date == NULL) {
                $query
                    ->whereBetween('data', [$start, $end]);
            }
        }

        // Se definisco l'evento
        if (!$evento == NULL) {
            $query
                ->where('descrizione', 'like', '%' . $evento . '%');
        }

        $holidays = $query->get();

        return view('index', compact('holidays', 'calendar'));
    }

    // Ordinare le festivitá
    public function order(Request $request)
    {
        // Per visualizzare il calendario
        $calendar = new Calendar();

        // Ottenere l'ordine da effettuare
        $ordinamento = $request->ordine;

        // Inizializzazione query
        $query = DB::table('holidays');

        // Se sono settati i filtri avviare i filtraggi prima dell'ordinamento
        if (isset($_POST['start_date'])) {
            // Se vengono definite le date
            if (!$_POST['start_date'] == NULL) {
                $start = Carbon::parse($_POST['start_date']);
                $start_giorno = $start->day;
                $start_mese = $start->month;
            }
            if (!$_POST['end_date'] == NULL) {
                $end = Carbon::parse($_POST['end_date']);
                $end_giorno = $end->day;
                $end_mese = $end->month;
            }
        }
        $evento = '';
        if (isset($_POST['descrizione'])) {
            // Se viene definito l'evento
            $evento = $_POST['descrizione'];
        } else {
            $evento == NULL;
        }

        // Se ricerco per anni e definisco le date
        if ($request->perAnni == 'si') {
            if (!$request->start_date == NULL && !$request->end_date == NULL) {
                $query
                    ->whereBetween(DB::raw('DAY(data)'), [$start_giorno, $end_giorno])
                    ->whereBetween(DB::raw('MONTH(data)'), [$start_mese, $end_mese])
                    ->where('ogni_anno', 1);
            } // Se non definisco le date
            else {
                $query
                    ->where('ogni_anno', 1);
            }
        } // Se non ricerco per anni e definisco le date
        else {
            if (!$request->start_date == NULL && !$request->end_date == NULL) {
                $query
                    ->whereBetween('data', [$start, $end]);
            }
        }

        // Se definisco l'evento
        if (!$evento == NULL) {
            $query
                ->where('descrizione', 'like', '%' . $evento . '%');
        }

        // Ordino i risultati in base all'ordinamento
        if ($ordinamento == 'creazione') {
            $query->orderBy('created_at', 'DESC');
        } else {
            $query->orderBy('data', 'ASC');
        }

        $holidays = $query->get();

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
        //
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
