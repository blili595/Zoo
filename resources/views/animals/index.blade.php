@extends('layouts.layout')

@section('content')
    

    @if($animals->isEmpty())
        <p>No archived animals available.</p>
    @else
    <h1>Archived Animals</h1>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Species</th>
                    <th>Deleted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($animals as $animal)
                    <tr>
                        <td>{{ $animal->name }}</td>
                        <td>{{ $animal->species }}</td>
                        <td>{{ \Carbon\Carbon::parse($animal->deleted_at)->format('M d, Y - h:i A') }}</td>
                        <td>
                        @if ($animal->deleted_at)
                <form action="{{ route('animals.restore', $animal->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <label for="enclosure_id_{{ $animal->id }}">Restore to Enclosure:</label>
                    <select name="enclosure_id" id="enclosure_id_{{ $animal->id }}" required>
                        <option value="">-- Select Enclosure --</option>
                        @foreach ($enclosures as $enclosure)
                            <option value="{{ $enclosure->id }}">
                                {{ $enclosure->name }} ({{ $enclosure->animals->count() }}/{{ $enclosure->limit }})
                            </option>
                        @endforeach
                    </select>
                    <button type="submit">Restore</button>
                </form>
            @endif
            @if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <br>
@endsection
