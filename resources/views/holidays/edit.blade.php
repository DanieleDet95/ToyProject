@extends('layouts.layout')

@section('main-content')
<h1 class="red">Modifica festivitá {{ $holiday->descrizione }}</h1>

  @if ($errors->any())
  <div class="alert alert-danger">
    <ul>
    @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
    </ul>
  </div>
  @endif

  <form action="{{ route('holidays.update', $holiday->id ) }}" method="post">
    @csrf
    @method('PUT')

    <label>Data:</label>  

    {{-- Agginge lo zero al mese e al giorno per vederlo nell'input di modifica --}}
    @php
    if ($holiday->giorno < 10) {
      $holiday->giorno = sprintf("%02d", $holiday->giorno);
    }
    if ($holiday->mese < 10) {
      $holiday->mese = sprintf("%02d", $holiday->mese);
    }
    @endphp

    <input name="data" type="date" value="{{ $holiday->anno }}-{{ $holiday->mese }}-{{ $holiday->giorno }}"><br>

    <label>Evento:</label>  
    <input name="descrizione" type="text" value="{{ $holiday->descrizione }}"><br>

    <label>Ogni anno?</label>
    <label>
    <input type="radio" name="ogni_anno" value="1" checked="checked">Si</label> 
    <label>
    <input type="radio" name="ogni_anno" value="2">No</label><br><br>

    <input type="submit" value="Modifica">
  </form>
  @endsection