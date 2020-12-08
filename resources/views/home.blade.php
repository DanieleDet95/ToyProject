@extends('layouts.layout')

@section('main-content')
<body class="bg-black">

    <h1 class="center red titolo">CALENDARIO DIGITALE</h1>
    <table class="entra" border="1">
        <tr>
            <td><a href="{{route('holidays.index')}}">Entra</a> </td>
        </tr>
    </table>

</body>
@endsection