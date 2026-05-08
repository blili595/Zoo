@extends('layouts.layout')

@section('content')
    <h1>Welcome, {{ Auth::user()->name }}!</h1>

    <section>
        <h2>Statistics</h2>
        <ul>
            <li>Total enclosures in system: {{ $enclosureCount }}</li>
            <li>Total animals in system: {{ $animals }}</li>
            @if(!auth()->user()->admin)
            <li>Your enclosures: {{ $enclosured->count() }}</li>
            @endif
        </ul>
    </section>

    <section>
        <h2>Your Enclosures & Feeding Times</h2>
        @if($enclosured->isEmpty())
            <p>You are not assigned to any enclosures yet.</p>
        @else
            <ul>
                @foreach($enclosured->sortBy(['feeding_at']) as $enclosure)
                    
                        
                        @if(\Carbon\Carbon::parse($enclosure->feeding_at)->isAfter(\Carbon\Carbon::now()))
                        <li>
                        <strong>{{ $enclosure->name }}</strong><br>
                        Feeding time: {{ \Carbon\Carbon::parse($enclosure->feeding_at)->format('F j, Y g:i A') }}
                        </li>
                    @endif
                    
                @endforeach
            </ul>
        @endif
    </section>
@endsection
