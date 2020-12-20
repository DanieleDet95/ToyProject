{{-- Per usare carbon nel blade --}}
@php
  use Carbon\Carbon;  
@endphp

@extends('layouts.layout')

@section('main-content')

  {{-- ------------------------------------------ Popup ------------------------------------------ --}} 
  {{-- Se sono presenti eventi nel giro di 31 giorni mostrare il popup --}}
  @if (!empty($holidays_show))
    <div class="popup" style="display: none">
      <h2>CIAO, TI VORREI SEGNALARE QUESTI EVENTI</h2>
      <a class="chiudi"><i class="fas fa-times"style="color: black;"></i></a>
      {{-- Se sono presenti dati con la chiave 'ieri' o 'oggi' stamparli qua --}}
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
  <div class="container-fluid black">
    <div class="row">
      <div class="col-md-6 col-sm-12">
        <h1 class="titolo_nuovo">AGGIUNGI UN NUOVO EVENTO</h1>
      </div>
    

      <div class="col-md-6 col-sm-12 right">
        <div class="float">
          <form action="{{ route('holidays.store') }}" method="post">
            @csrf
            @method('POST')

              <fieldset class="tabella_create">

                <!-- Creare nuovo evento -->
                <legend>INSERISCI EVENTO</legend>

                <div>

                  {{-- Data --}}
                  <label>Data:</label>  
                  <input name="data" type="text" class="datainput"><br>

                  {{-- Evento --}}
                  <label>Evento:</label>  
                  <input name="descrizione" type="text"><br>

                  {{-- Si ripete ogni anno(facoltativo) --}}
                  <div class="anno">
                    <input type="checkbox" id="ogni_anno">
                    <label class="anno">OGNI ANNO?</label>
                    <input id="ogni_anno_1" type="radio" name="ogni_anno" value="1" disabled>SI 
                    <input id="ogni_anno_2" type="radio" name="ogni_anno" value="2" disabled>NO
                  </div>

                  {{-- Invio dati --}}
                  <input id="inserisci" class="btn_inserisci" type="submit" value="INSERISCI">
                </div>

              </fieldset>
          </form>
        </div>
        
      </div>
      <hr>
    </div>
  </div>
  
  {{-- --------------------------------- Fine Aggiunta nuovo evento ------------------------------------- --}}
  <div class="container-fluid white">
    {{-- --------------------------------------- Lista eventi --------------------------------------------- --}}
      <div class="row filtro">
        <div class="col-md-6 col-sm-12">
          <h1 class="titolo_lista">LISTA DELLE FESTE</h1>
        </div>

        <div class="col-md-6 col-sm-12 right">

          {{-- ------------------------------ Filtrare e ordinare gli eventi --------------------------------- --}}
          <form action="{{ route('holidays.filter') }}" method="post">
          @csrf
          @method('post')

          {{-- Per data --}}
          <input type="checkbox" id="filtroData" value="data">
          <label>Data</label>
          <input type="text" id="startDate" class="datainput" name="start_date" placeholder="Inizio" disabled>
          <input type="text" id="endDate" class="datainput data_fine" name="end_date" placeholder="Fine" disabled><br>
          {{-- Per evento --}}
          <input type="checkbox" id="filtroEvento" value="evento">
          <label>Evento</label>
          <input id="evento" name="descrizione" type="text" disabled>
          <div class="anno">
            <label class="anno">CERCA NEGLI ANNI? </label> 
            <input id="anni_si" type="radio" name="perAnni" value="si" disabled>Si</label>
            <input id="anni_no" type="radio" name="perAnni" value="no" checked="checked" disabled>No</label>
          </div>
          {{-- Invio e reset dati --}}
          <input class="btn_filtra" type="submit" value="FILTRA">
          <button class="reset">RESET</button>
          </form>
        
        </div>
        <hr>
      </div>

      <div class="row ordina">
        <div class="col-md-12 col-sm-12">

          {{-- ------------------------------- Ordinazione gli eventi ----------------------------------------- --}}
          <div>
            <form action="{{ route('holidays.order') }}" method="post">
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
            <label>Ordina per </label>
            <select class="ordinazione" name="ordine">
              <option value="creazione" checked>Creazione</option>
              <option value="cronologico">Cronologico</option>
            </select>
            <input class="btn_ordina" type="submit" value="ORDINA">
          </h5>
          </form>
          </div>

        </div>
      </div>    

    {{-- --------------------------------- Tabella Elenco degli eventi ------------------------------------- --}}
    <table class="lista" border="1">
      <tr>
        <th>DATA</th>
        <th>DESCRIZIONE</th>
        <th>OGNI ANNO?</th>
        <th>COPIA NEGLI APPUNTI</th>
        <th>ELIMINA</th>
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
          <td class="rigaCopia">

            {{-- Per ricavare il risultato per copiare negli appunti --}}
            <button class="copia">Copia</button>
            <input class="giorno" type="text" value="{{$giorno}}" style = "position: absolute; left: -1000px; top: -1000px">
            <input class="mese" type="text" value="{{$mese}}" style = "position: absolute; left: -1000px; top: -1000px">
            <input class="anno" type="text" value="{{$anno}}" style = "position: absolute; left: -1000px; top: -1000px">
            <input class="evento" type="text" value="{{$holiday->descrizione}}" style = "position: absolute; left: -1000px; top: -1000px">
          </td>
          <td class="rigaElimina">

            {{-- Eliminazione --}}
            <form action='{{ route('holidays.destroy', $holiday->id ) }}' method='post'>
              @csrf
              @method('DELETE')
              <input class='elimina' type='submit' value='X'>
            </form>
          </td>
        </tr>
      @endforeach
    </table>
  </main>
  

  {{-- Calendario web --}}
  {{-- <div class="content-fluid">
    <div class="row">
      <div class="col-12">
        @php
          if (isset($calendar)) {
            echo $calendar->show();
          }
        @endphp
      </div>
    </div>
  </div> --}}

@endsection