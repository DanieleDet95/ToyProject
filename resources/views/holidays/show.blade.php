@extends('layouts.layout')

@section('main-content')
<h1>Festa creata</h1>

    <ul>
        <li>Giorno: {{ $holiday->data }}</li>
        <li>Nome: {{ $holiday->descrizione }}</li>
        <li>Ogni anno? {{ $holiday->ogni_anno }}</li>
        <li><a href="{{route('holidays.edit', $holiday->id)}}">Modifica evento</a></li>
    </ul>

<h3><a href="{{ route('home')}}">HOME</a></h3>

@endsection