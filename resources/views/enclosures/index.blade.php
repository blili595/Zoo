@extends('layouts.layout')

@section('content')
    <h1>Enclosures</h1>

    @if($enclosures->isEmpty())
        <p>No enclosures available.</p>
    @else
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Animal Limit</th>
                    <th>Current Animal Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enclosures->sortBy(['name']) as $enclosure)
                    <tr>
                        <td>{{ $enclosure->name }}</td>
                        <td>{{ $enclosure->limit }}</td>
                        <td>{{ $enclosure->animals->count() }}</td>
                        <td>
                            <a href="{{ route('enclosures.show', $enclosure->id) }}">Show</a>

                            @if(auth()->user()->admin)
                                <a href="{{ route('enclosures.edit', $enclosure->id) }}">Edit</a>

                                <form action="{{ route('enclosures.destroy', $enclosure->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Pagination Links -->
        <div>
            {{ $enclosures->links() }}
        </div>
        
    @endif
@endsection
