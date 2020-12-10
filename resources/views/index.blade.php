@extends('layouts.layout')

@section('main-content')

{{-- ------------------------------------------ Popup ------------------------------------------ --}} 
{{-- Se somno presenti eventi nel giro di 31 giorni mostrare il popup --}}
@if (!empty($holidays_show))
  <div class="popup" style="display: none">
    <a class="chiudi"><i class="fas fa-times"></i></a>
    @if (isset($holidays_show['ieri']) || (isset($holidays_show['oggi'])))
      <h2>Ciao ti vorrei segnalare questi eventi recenti:</h2>
      <h3>
        @if (isset($holidays_show['ieri']))
          Ieri: {{$holidays_show['ieri']->descrizione }}
        @endif
      </h3>
      <h3>
        @if (isset($holidays_show['oggi']))
          Oggi: {{$holidays_show['oggi']->descrizione }}
        @endif
      </h3>
    @endif
    <h3>In questi giorni hai questi eventi:</h3>
    <ul>
      @foreach ($holidays_show as $holiday_show => $holiday)
      
        {{-- Escludere ieri e oggi dai risultati --}}
        @if (!($holiday_show === 'ieri' || $holiday_show === 'oggi'))
          <li>
            <strong>{{ $holiday->descrizione }}({{ $holiday->giorno }}
            @if ($holiday->mese == 1)Gennaio
            @elseif ($holiday->mese == 2)Febbraio 
            @elseif ($holiday->mese == 3)Marzo 
            @elseif ($holiday->mese == 4)Aprile 
            @elseif ($holiday->mese == 5)Maggio 
            @elseif ($holiday->mese == 6)Giugno 
            @elseif ($holiday->mese == 7)Luglio 
            @elseif ($holiday->mese == 8)Agosto
            @elseif ($holiday->mese == 9)Settembre 
            @elseif ($holiday->mese == 10)Ottobre 
            @elseif ($holiday->mese == 11)Novembre 
            @elseif ($holiday->mese == 12)Dicembre 
            @endif
            {{ $holiday->anno }})</strong>
          </li>
        @endif
        
      @endforeach
    </ul>
  </div>  
@endif
{{-- ------------------------------------------Fine Popup ------------------------------------------ --}} 


{{-- ----------------------------------- Aggiunta nuovo evento ------------------------------------- --}}
<h1 class="center red">Aggiungi nuovo evento</h1>

<form class="form-horizontal" action="{{ route('holidays.store') }}" method="post">
@csrf
@method('POST')

  <fieldset class="tabella_create">

    <!-- Creare nuovo evento -->
    <legend class="center red bg-black">Inserisci Evento</legend>

    <div class="center">

      {{-- Data --}}
      <label>Data:</label>  
      <input name="data" type="date"><br>

      {{-- Evento --}}
      <label>Evento:</label>  
      <input name="descrizione" type="text"><br>

      {{-- Si ripete ogni anno(facoltativo) --}}
      <input type="checkbox" id="ogni_anno">
      <label>Ogni anno?</label>
      <label>
      <input id="ogni_anno_1" type="radio" name="ogni_anno" value="1" disabled>Si</label> 
      <label>
      <input id="ogni_anno_2" type="radio" name="ogni_anno" value="2" disabled>No</label><br><br>

      {{-- Invio dati --}}
      <input class="bg-red" type="submit" value="Inserisci">
    </div>

  </fieldset>
</form>
{{-- --------------------------------- Fine Aggiunta nuovo evento ------------------------------------- --}}

{{-- --------------------------------------- Lista eventi --------------------------------------------- --}}
<h1 class="center red">Lista feste</h1>

<div class="flex">

  {{-- ---------------------------------- Filtrare gli eventi ----------------------------------------- --}}
  <form class="form-left" action="{{ route('holidays.filter') }}" method="post">
  @csrf
  @method('POST')

  {{-- Per data --}}
  <h3>
    <input type="checkbox" id="filtroData" value="data">
    Data da: <input id="startDate" name="start_date" type="date" disabled> 
    a <input id="endDate" name="end_date" type="date" disabled><br>
    Cerca negli anni? 
    <input id="anni_si" type="radio" name="perAnni" value="si" checked="checked" disabled>Si</label>
    <input id="anni_no" type="radio" name="perAnni" value="no" disabled>No</label>
  </h3>

  {{-- Per evento --}}
  <h3>
    
    <input type="checkbox" id="filtroEvento" value="evento">
    Evento: <input id="evento" name="descrizione" type="text" disabled>
  </h3>

  {{-- Invio e reset dati --}}
  <input class="bg-red" type="submit" value="Filtra">
  <button class="reset bg-blue">Reset</button>
  </form>
  

  {{-- ------------------------------- Ordinazione gli eventi ----------------------------------------- --}}
  <form class="form-right" action="{{ route('holidays.order') }}" method="post">
  @csrf
  @method('POST')
  <h3>
    <label>Ordina per:</label>
    <select name="ordine">
      <option value="1" checked>Creazione</option>
      <option value="2">Cronologico</option>
    </select>
    <input class="bg-red" type="submit" value="Ordina">
  </h3>
  </form>

</div>


{{-- --------------------------------- Tabella Elenco degli eventi ------------------------------------- --}}
<table class="center lista" border="1">
  <tr>
    <th>Data</th>
    <th>Descrizione</th>
    <th>Ogni anno?</th>
    <th>Modifica</th>
    <th>Elimina</th>
    <th>Copia negli appunti</th>
  </tr>

  {{-- Se gli passo l'opzione cerca negli anni --}}
  @if (isset($holidaysAnni))

    {{-- Cicla negli anni --}}
    @foreach ($holidaysAnni as $holidays)
      @foreach ($holidays as $holiday)
        <tr>

          {{-- data ed evento --}}
          <td>{{$holiday->giorno}}
            @if ($holiday->mese == 1)Gennaio
            @elseif ($holiday->mese == 2)Febbraio 
            @elseif ($holiday->mese == 3)Marzo 
            @elseif ($holiday->mese == 4)Aprile 
            @elseif ($holiday->mese == 5)Maggio 
            @elseif ($holiday->mese == 6)Giugno 
            @elseif ($holiday->mese == 7)Luglio 
            @elseif ($holiday->mese == 8)Agosto
            @elseif ($holiday->mese == 9)Settembre 
            @elseif ($holiday->mese == 10)Ottobre 
            @elseif ($holiday->mese == 11)Novembre 
            @elseif ($holiday->mese == 12)Dicembre 
            @endif
            {{$holiday->anno}}</td>
          <td>{{ $holiday->descrizione }}</td>
          <td>

            {{-- Se si verifica ogni anno --}}
            @if ($holiday->ogni_anno == 1) Si
            @else No
            @endif
          </td>
          <td><a class="text-dec-none" href="{{route('holidays.edit', $holiday->id)}}">Modifica evento</a></td>
          <td>

            {{-- Eliminazione --}}
            <form action="{{ route('holidays.destroy', $holiday->id ) }}" method="post">
              @csrf
              @method('DELETE')
              <input class="w-100 bg-red" type="submit" value="X"></a>
            </form>
          </td>
          <td>

            {{-- Per ricavare il risultato per copiare negli appunti --}}
            <button class="copia w-100 bg-blue">Copia</button>
            <input class="giorno" type="text" value="{{$holiday->giorno}}" style = "position: absolute; left: -1000px; top: -1000px">
            <input class="mese" type="text" value="{{$holiday->mese}}" style = "position: absolute; left: -1000px; top: -1000px">
            <input class="anno" type="text" value="{{$holiday->anno}}" style = "position: absolute; left: -1000px; top: -1000px">
            <input class="evento" type="text" value="{{$holiday->descrizione}}" style = "position: absolute; left: -1000px; top: -1000px">
          </td>
        </tr>
      @endforeach
      
    @endforeach
    </table>
  
  {{-- Se non gli passo l'opzione cerca negli anni --}}
  @else

     @foreach ($holidays as $holiday)
      <tr>

        {{-- Data ed evento --}}
        <td>{{$holiday->giorno}}
            @if ($holiday->mese == 1)Gennaio
            @elseif ($holiday->mese == 2)Febbraio 
            @elseif ($holiday->mese == 3)Marzo 
            @elseif ($holiday->mese == 4)Aprile 
            @elseif ($holiday->mese == 5)Maggio 
            @elseif ($holiday->mese == 6)Giugno 
            @elseif ($holiday->mese == 7)Luglio 
            @elseif ($holiday->mese == 8)Agosto
            @elseif ($holiday->mese == 9)Settembre 
            @elseif ($holiday->mese == 10)Ottobre 
            @elseif ($holiday->mese == 11)Novembre 
            @elseif ($holiday->mese == 12)Dicembre 
            @endif
            {{$holiday->anno}}</td>
        <td>{{ $holiday->descrizione }}</td>
        <td>

          {{-- Se si verifica ogni anno --}}
          @if ($holiday->ogni_anno == 1) Si
          @else No
          @endif
        </td>
        <td><a class="text-dec-none" href="{{route('holidays.edit', $holiday->id)}}">Modifica evento</a></td>
        <td>

          {{-- Eliminazione --}}
          <form action="{{ route('holidays.destroy', $holiday->id ) }}" method="post">
            @csrf
            @method('DELETE')
            <input class="w-100 bg-red" type="submit" value="X"></a>
          </form>
        </td>
        <td>

          {{-- Per ricavare il risultato per copiare negli appunti --}}
          <button class="copia w-100 bg-blue">Copia</button>
          <input class="giorno" type="text" value="{{$holiday->giorno}}" style = "position: absolute; left: -1000px; top: -1000px">
          <input class="mese" type="text" value="{{$holiday->mese}}" style = "position: absolute; left: -1000px; top: -1000px">
          <input class="anno" type="text" value="{{$holiday->anno}}" style = "position: absolute; left: -1000px; top: -1000px">
          <input class="evento" type="text" value="{{$holiday->descrizione}}" style = "position: absolute; left: -1000px; top: -1000px">
        </td>
      </tr>
    @endforeach
    </table>
      
  @endif

  {{-- Calendario web --}}
  @php
    if (isset($calendar)) {
      echo $calendar->show();
    }
  @endphp

@endsection