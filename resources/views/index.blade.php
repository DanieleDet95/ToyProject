{{-- Per usare carbon nel blade --}}
@php
  use Carbon\Carbon;  
@endphp

{{-- Sistemare piccolo bug al click di reset non visualizzo il calendario--}}
@extends('layouts.layout')

@section('main-content')

  {{-- ------------------------------------------ Popup ------------------------------------------ --}} 
  {{-- Se somno presenti eventi nel giro di 31 giorni mostrare il popup --}}
  @if (!empty($holidays_show))
    <div class="popup" style="display: none">
      <h2>Ciao ti vorrei segnalare questi eventi:</h2>
      <a class="chiudi"><i class="fas fa-times"style="color: black;"></i></a>
      @if (isset($holidays_show['ieri']) || (isset($holidays_show['oggi'])))
        <h3>
          @if (isset($holidays_show['ieri']))
            Successe ieri: {{$holidays_show['ieri']->descrizione }}
          @endif
        </h3>
        <h3>
          @if (isset($holidays_show['oggi']))
            Successe oggi: {{$holidays_show['oggi']->descrizione }}
          @endif
        </h3>
      @endif
      <ul>
        @foreach ($holidays_show as $holiday_show => $holiday)
        
          {{-- Escludere ieri e oggi dai risultati --}}
          @if (!($holiday_show === 'ieri' || $holiday_show === 'oggi'))
            <li>
              @php
                $data = Carbon::parse($holiday->data);
                $giorno = $data->day;
                $mese = $data->month;
                $anno = $data->year;
              @endphp
              <strong>{{ $holiday->descrizione }}({{ $giorno }}
              @if ($mese == 1)Gennaio
              @elseif ($mese == 2)Febbraio 
              @elseif ($mese == 3)Marzo 
              @elseif ($mese == 4)Aprile 
              @elseif ($mese == 5)Maggio 
              @elseif ($mese == 6)Giugno 
              @elseif ($mese == 7)Luglio 
              @elseif ($mese == 8)Agosto
              @elseif ($mese == 9)Settembre 
              @elseif ($mese == 10)Ottobre 
              @elseif ($mese == 11)Novembre 
              @elseif ($mese == 12)Dicembre 
              @endif
              {{ $anno }})</strong>
            </li>
          @endif
          
        @endforeach
      </ul>
    </div>  
  @endif
  {{-- ------------------------------------------Fine Popup ------------------------------------------ --}} 


  {{-- ----------------------------------- Aggiunta nuovo evento ------------------------------------- --}}
  <h1 class="center red">Aggiungi nuovo evento</h1>

  <form class="form" action="{{ route('holidays.store') }}" method="post">
  @csrf
  @method('POST')

    <fieldset class="tabella_create">

      <!-- Creare nuovo evento -->
      <legend class="center red bg-black">Inserisci Evento</legend>

      <div class="center pb-3">

        {{-- Data --}}
        <label>Data:</label>  
        <input name="data" type="text" class="datainput"><br>

        {{-- Evento --}}
        <label>Evento:</label>  
        <input name="descrizione" type="text"><br>

        {{-- Si ripete ogni anno(facoltativo) --}}
        <input type="checkbox" id="ogni_anno">
        <label>Ogni anno?</label>
        <input id="ogni_anno_1" type="radio" name="ogni_anno" value="1" disabled>Si 
        <input id="ogni_anno_2" type="radio" name="ogni_anno" value="2" disabled>No<br><br>

        {{-- Invio dati --}}
        <input id="inserisci" class="bg-red" type="submit" value="Inserisci">
      </div>

    </fieldset>
  </form>
  {{-- --------------------------------- Fine Aggiunta nuovo evento ------------------------------------- --}}
  {{-- --------------------------------------- Lista eventi --------------------------------------------- --}}
  <h1 class="center red p-3">Lista feste</h1>

  <div class="row pb-4">
    <div class="col-md-6 col-sm-12">

      {{-- ------------------------------ Filtrare e ordinare gli eventi --------------------------------- --}}
      <form class="form-left" action="{{ route('holidays.filter') }}" method="post">
      @csrf
      @method('post')

      {{-- Per data --}}
      <h5>
        <input type="checkbox" id="filtroData" value="data">
        Data da: <input type="text" id="startDate" class="datainput" name="start_date" disabled> 
        a <input type="text" id="endDate" class="datainput" name="end_date" disabled><br>
        Cerca negli anni? 
        <input id="anni_si" type="radio" name="perAnni" value="si" disabled>Si</label>
        <input id="anni_no" type="radio" name="perAnni" value="no" checked="checked" disabled>No</label>
      </h5>

      {{-- Per evento --}}
      <h5>
        
        <input type="checkbox" id="filtroEvento" value="evento">
        Evento: <input id="evento" name="descrizione" type="text" disabled>
      </h5>

      {{-- Invio e reset dati --}}
      <input class="bg-red" type="submit" value="Filtra">
      <button class="reset bg-blue">Reset</button>
      </form>
    
    </div>
    <div class="col-md-6 col-sm-12 d-flex flex-row-reverse">

      {{-- ------------------------------- Ordinazione gli eventi ----------------------------------------- --}}
      <form class="form-right" action="{{ route('holidays.order') }}" method="post">
      @csrf
      @method('POST')

      {{-- Mantengo i filtri effettuati --}}
      <?php if(isset($_POST['start_date'])){ ?>
          <input type="text" name="start_date" value="{{$_POST['start_date']}}" hidden>
          <input type="text" name="end_date" value="{{$_POST['end_date']}}" hidden>
          <input type="text" name="perAnni" value="{{$_POST['perAnni']}}" hidden>
      <?php } ?>
      <?php if(isset($_POST['descrizione'])){ ?>
          <input type="text" name="descrizione" value="{{$_POST['descrizione']}}" hidden>
      <?php } ?>
      
      <h5>
        <label>Ordina per:</label>
        <select class="ordinazione" name="ordine">
          <option value="creazione" checked>Creazione</option>
          <option value="cronologico">Cronologico</option>
        </select>
        <input class="bg-red ordina" type="submit" value="Ordina">
      </h5>
      </form>

    </div>
  </div>

  {{-- --------------------------------- Tabella Elenco degli eventi ------------------------------------- --}}
  <table class="center lista" border="1">
    <tr>
      <th>Data</th>
      <th>Descrizione</th>
      <th>Ogni anno?</th>
      <th>Elimina</th>
      <th>Copia negli appunti</th>
    </tr>

    @foreach ($holidays as $holiday)
      <tr>

        {{-- Data ed evento --}}
        @php
          $data = Carbon::parse($holiday->data);
          $giorno = $data->day;
          $mese = $data->month;
          $anno = $data->year;
        @endphp
        <td>{{$giorno}}
          @if ($mese == 1)Gennaio
          @elseif ($mese == 2)Febbraio 
          @elseif ($mese == 3)Marzo 
          @elseif ($mese == 4)Aprile 
          @elseif ($mese == 5)Maggio 
          @elseif ($mese == 6)Giugno 
          @elseif ($mese == 7)Luglio 
          @elseif ($mese == 8)Agosto
          @elseif ($mese == 9)Settembre 
          @elseif ($mese == 10)Ottobre 
          @elseif ($mese == 11)Novembre 
          @elseif ($mese == 12)Dicembre 
          @endif
          {{$anno}}
        </td>
        <td>{{ $holiday->descrizione }}</td>
        <td>

          {{-- Se si verifica ogni anno --}}
          @if ($holiday->ogni_anno == 1) Si
          @else No
          @endif
        </td>
        <td class="rigaElimina">

          {{-- Eliminazione --}}
          <form action='{{ route('holidays.destroy', $holiday->id ) }}' method='post'>
            @csrf
            @method('DELETE')
            <input class='w-100 bg-red' type='submit' value='X'>
          </form>
        </td>
        <td class="rigaCopia">

          {{-- Per ricavare il risultato per copiare negli appunti --}}
          <button class="copia w-100 bg-blue">Copia</button>
          <input class="giorno" type="text" value="{{$giorno}}" style = "position: absolute; left: -1000px; top: -1000px">
          <input class="mese" type="text" value="{{$mese}}" style = "position: absolute; left: -1000px; top: -1000px">
          <input class="anno" type="text" value="{{$anno}}" style = "position: absolute; left: -1000px; top: -1000px">
          <input class="evento" type="text" value="{{$holiday->descrizione}}" style = "position: absolute; left: -1000px; top: -1000px">
        </td>
      </tr>
    @endforeach
  </table>

  {{-- Calendario web --}}
  @php
    if (isset($calendar)) {
      echo $calendar->show();
    }
  @endphp

@endsection