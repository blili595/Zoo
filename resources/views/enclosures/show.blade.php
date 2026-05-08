@extends('layouts.layout')

@section('content')
    <h1>Enclosure: {{ $enclosure->name }}</h1>
    @if($enclosure->is_predator)
    <p style="color: red; font-weight: bold;">Warning: This is a predator enclosure!</p>
@endif
    <p><strong>Animal Limit:</strong> {{ $enclosure->limit }}</p>
    <strong>Next Feeding: </strong>{{ \Carbon\Carbon::parse($enclosure->feeding_at)->format('F j, Y - g:i A') }}<br>

    <p><strong>Current Animals:</strong> {{ $enclosure->animals->count() }}</p>

    

    @if($enclosure->animals->isEmpty())
        <p>No animals in this enclosure.</p>
    @else
    <h2>Animals in this Enclosure</h2>
        <ul>
            @foreach($enclosure->animals->sortBy(['species', 'born_at']) as $animal)
                <li>
                    <strong>{{ $animal->name }}</strong> ({{ $animal->species }}) ({{ $animal->born_at }})<br>
                    
            <img src="{{ $animal->picture ? asset('storage/' . $animal->picture) :  asset('storage/images/placeholder.png') }}" alt="{{ $animal->name }}" width="150">
                    
                    <a href="{{ route('animals.show', $animal->id) }}">Show</a>
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('enclosures.index') }}">Back to Enclosure List</a>
@endsection
