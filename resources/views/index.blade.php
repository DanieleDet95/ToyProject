@extends('layouts.layout')

@section('main-content')

{{-- Popup --}}
@if (isset($holidays_show))
  <div class="popup">
    <a class="chiudi"><i class="fas fa-times"></i></a>
    <h2>Ciao ti vorrei segnalare questi eventi recenti:</h2>
    <h3>
      @if (isset($holidays_show['ieri']))
        Ieri: {{$holidays_show['ieri']->descrizione }}({{ $holidays_show['ieri']->giorno }}/{{ $holidays_show['ieri']->mese }}/{{ $holidays_show['ieri']->anno }})
      @endif
    </h3>
    <h3>
      @if (isset($holidays_show['oggi']))
        Oggi: {{$holidays_show['oggi']->descrizione }}({{ $holidays_show['oggi']->giorno }}/{{ $holidays_show['oggi']->mese }}/{{ $holidays_show['ieri']->anno }})
      @endif
    </h3>
    <h3>In questi giorni hai questi eventi:</h3>
    <ul>
      @foreach ($holidays_show as $holiday_show => $holiday)

        {{-- Escludere ieri e oggi dai risultati --}}
        @if (!($holiday_show == 'ieri' || $holiday_show == 'oggi'))
            <li><strong>{{ $holiday->descrizione }}({{ $holiday->giorno }}/{{ $holiday->mese }}/{{ $holiday->anno }})</strong></li>
        @endif
        
      @endforeach
    </ul>
  </div>  
@endif


{{-- Aggiunta nuovo evento --}}
<h1 class="center red">Aggiungi nuovo evento</h1>

<form class="form-horizontal" action="{{ route('holidays.store') }}" method="post">
@csrf
@method('POST')

<fieldset class="tabella_create">

<!-- Creare nuovo evento -->
<legend class="center red bg-black">Inserisci Evento</legend>

<div class="center">
    <label>Data:</label>  
    <input name="data" type="date"><br>

    <label>Evento:</label>  
    <input name="descrizione" type="text"><br>

    <label>Ogni anno?</label>
    <label>
    <input type="radio" name="ogni_anno" value="1" checked="checked">Si</label> 
    <label>
    <input type="radio" name="ogni_anno" value="2">No</label><br><br>

    <input class="bg-red" type="submit" value="Inserisci">
</div>

</fieldset>
</form>

{{-- Lista eventi --}}
<h1 class="center red">Lista feste</h1>

<div class="flex">

  {{-- Filtrare gli eventi --}}
  <form class="form-left" action="{{ route('holidays.filter') }}" method="post">
  @csrf
  @method('POST')
  <h3>
    <input type="checkbox" id="filtroData" value="data">
    Data da: <input id="startDate" name="start_date" type="date"> 
    a <input id="endDate" name="end_date" type="date"><br>
    Cerca negli anni? 
    <input id="anni_si" type="radio" name="perAnni" value="si" checked="checked">Si</label>
    <input id="anni_no" type="radio" name="perAnni" value="no">No</label></h3>
  <h3>
    <input type="checkbox" id="filtroEvento" value="evento">
    Evento: <input id="evento" name="descrizione" type="text"></h3>

  <input class="bg-red" type="submit" value="Filtra">
  <button class="reset bg-blue">Reset</button>
  </form>
  

  {{-- Ordinazione gli eventi --}}
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


{{-- Elenco eventi --}}
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

          {{-- Info evento --}}
          <td>{{$holiday->giorno}}-{{$holiday->mese}}-{{$holiday->anno}}</td>
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

        {{-- Info evento --}}
        <td>{{$holiday->giorno}}-{{$holiday->mese}}-{{$holiday->anno}}</td>
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

@endsection