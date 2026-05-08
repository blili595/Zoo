@extends('layouts.layout')

@section('content')
    <h1>{{ $animal->name }} ({{ $animal->species }})</h1>

    

    <div>
        <strong>Enclosure:</strong> 
        {{ $animal->enclosure->name }} 
    </div>

    
        <div>
            <strong>Image:</strong>
            <img src="{{ $animal->picture ? asset('storage/' . $animal->picture) :  asset('storage/images/placeholder.png') }}" alt="{{ $animal->name }}" width="150">
        </div>
    

    <br>
    @if(auth()->user()->admin)
    <a href="{{ route('animals.edit', $animal->id) }}">Edit Animal</a>
    <form method="POST" action="{{ route('animals.destroy', $animal->id) }}" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Are you sure you want to delete this animal?')">Delete Animal</button>
    </form>
    @endif
    <br><br>
    <a href="{{ route('enclosures.index') }}">Back to Enclosure List</a>
@endsection
