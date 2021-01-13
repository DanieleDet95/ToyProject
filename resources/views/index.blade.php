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
  <div class="container black">
    <div class="row bg-dark text-white p-3 justify-content-between">
      <div class="col-sm-12 col-md-7">
        <h1 class="titolo_nuovo text-md-left text-center pt-4">AGGIUNGI UN NUOVO EVENTO</h1>
      </div>
    
      <div class="col-md-1"></div>

      <div class="col-sm-12 col-md-4">
        <div class="float text-md-left text-center">
          <form action="{{ route('holidays.store') }}" method="post">
            @csrf
            @method('POST')

              <fieldset class="tabella_create">

                <!-- Creare nuovo evento -->
                <legend>INSERISCI EVENTO</legend>

                <div>

                  {{-- Id --}}
                  <input name="id" type="text" class="id" hidden>

                  {{-- Data --}}
                  <label>Data:</label><br>
                  <input name="data" type="text" class="datainput"><br>

                  {{-- Evento --}}
                  <label>Evento:</label><br>
                  <input name="descrizione" type="text"><br>

                  {{-- Si ripete ogni anno(facoltativo) --}}
                  <div class="anno">
                    <input type="checkbox" id="ogni_anno">
                    <label class="anno">OGNI ANNO?</label>
                    <input id="ogni_anno_1" type="radio" name="ogni_anno" value="1" disabled>SI 
                    <input id="ogni_anno_2" type="radio" name="ogni_anno" value="2" disabled>NO
                  </div>

                  {{-- Invio dati --}}
                  <input id="inserisci" class="btn_inserisci btn btn-success" type="submit" value="SALVA">
                </div>

              </fieldset>
          </form>
        </div>
        
      </div>
      <hr>
    </div>
  </div>
  
  {{-- --------------------------------- Fine Aggiunta nuovo evento ------------------------------------- --}}
  <div class="container white">
    {{-- --------------------------------------- Lista eventi --------------------------------------------- --}}
      <div class="row filtro p-3">
        <div class=" col-sm-12 col-md-5">
          <h1 class="titolo_lista text-md-left text-center pt-4">LISTA DELLE FESTE</h1>
        </div>

        <div class="col-md-3"></div>

        <div class="col-sm-12 col-md-4  right">

          <div class="form_lista text-md-left text-center">

            <legend>FILTRA EVENTO</legend>

            {{-- ------------------------------ Filtrare e ordinare gli eventi --------------------------------- --}}
            <form action="{{ route('holidays.filter') }}" method="post">
              @csrf
              @method('post')

              {{-- Per data --}}
              <input type="checkbox" id="filtroData" value="data">
              <label>Data</label><br>
              <input type="text" id="startDate" class="datainput" name="start_date" placeholder="Inizio" disabled><br>
              <input type="text" id="endDate" class="datainput data_fine" name="end_date" placeholder="Fine" disabled><br>
              {{-- Per evento --}}
              <input type="checkbox" id="filtroEvento" value="evento">
              <label>Evento</label><br>
              <input id="evento" name="descrizione" type="text" disabled>
              <div class="anno">
                <label class="anno">CERCA NEGLI ANNI? </label> <br>
                <input id="anni_si" type="radio" name="perAnni" value="si" disabled>Si</label>
                <input id="anni_no" type="radio" name="perAnni" value="no" checked="checked" disabled>No</label>
              </div>
              {{-- Invio e reset dati --}}
              <input class="btn_filtra btn btn-success" type="submit" value="FILTRA">
              <button class="reset btn btn-danger">RESET</button>
            </form>

          </div>
        
        </div>
        <hr>
      </div>

      <div class="row ordina pt-3 text-center">
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
            <select class="ordinazione form-control-sm" name="ordine">
              <option value="creazione" checked>Creazione</option>
              <option value="cronologico">Cronologico</option>
            </select>
            <input class="btn_ordina btn btn-success" type="submit" value="ORDINA">
          </h5>
          </form>
          </div>

        </div>
      </div>    

    {{-- --------------------------------- Tabella Elenco degli eventi ------------------------------------- --}}
    <div class="row tabella pt-3">
      <div class="col-md-12 col-sm-12">
        <table class="lista text-center table table-striped table-responsive-sm" border="1">
          <tr>
            <th class="p-2">DATA</th>
            <th class="p-2">DESCRIZIONE</th>
            <th class="p-2">OGNI ANNO?</th>
            <th class="p-2">COPIA NEGLI APPUNTI</th>
            <th class="p-2">MODIFICA</th>
            <th class="p-2">ELIMINA</th>
          </tr>

          @foreach ($holidays as $holiday)
          {{-- Data ed evento --}}
              @php
                $data = Carbon::parse($holiday->data);
                $giorno = $data->day;
                $mese = $data->month;
                if ($mese == 1)$meseNome="Gennaio";
                elseif ($mese == 2)$meseNome="Febbraio";
                elseif ($mese == 3)$meseNome="Marzo"; 
                elseif ($mese == 4)$meseNome="Aprile"; 
                elseif ($mese == 5)$meseNome="Maggio"; 
                elseif ($mese == 6)$meseNome="Giugno"; 
                elseif ($mese == 7)$meseNome="Luglio"; 
                elseif ($mese == 8)$meseNome="Agosto";
                elseif ($mese == 9)$meseNome="Settembre"; 
                elseif ($mese == 10)$meseNome="Ottobre"; 
                elseif ($mese == 11)$meseNome="Novembre"; 
                elseif ($mese == 12)$meseNome="Dicembre"; 
                $anno = $data->year;
              @endphp
              
            <tr data-copia="{{$holiday->descrizione}}-{{$giorno}} {{$meseNome}} {{$anno}}">

              <td class="id" data-id="{{$holiday->id}}" hidden></td>

              <td class="data" data-giorno="{{$anno}}/{{$mese}}/{{$giorno}}">
                {{$giorno}}
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
              <td class="descrizione "data-descrizione="{{$holiday->descrizione}}">{{ $holiday->descrizione }}</td>
              <td class="anno" data-anno="{{$holiday->ogni_anno}}">

                {{-- Se si verifica ogni anno --}}
                @if ($holiday->ogni_anno == 1) Si
                @else No
                @endif
              </td>

              {{-- Copia --}}
              <td class="rigaCopia">

                {{-- Per ricavare il risultato per copiare negli appunti --}}
                <button class="copia btn btn-info w-100">Copia</button>
              </td>

              {{-- Modifica --}}
              <td class="rigaModifica">

                {{-- Per ricavare il risultato per copiare negli appunti --}}
                <button class="modifica btn btn-warning w-100">Modifica</button>
              </td>

              {{-- Elimina --}}
              <td class="rigaElimina">

                {{-- Eliminazione --}}
                <form class="elimina" action='{{ route('holidays.destroy', $holiday->id ) }}' method='post'>
                  @csrf
                  @method('DELETE')
                  <input class='elimina btn btn-danger w-100' type='submit' value='X'>
                </form>
              </td>

            </tr>
          @endforeach
        </table>
      </div>
    </div>
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