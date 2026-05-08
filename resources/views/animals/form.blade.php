@extends('layouts.layout')

@section('content')
    <h1>{{ isset($animal) ? 'Edit Animal' : 'Add New Animal' }}</h1>

    <form 
        action="{{ isset($animal) ? route('animals.update', $animal->id) : route('animals.store') }}" 
        method="POST" 
        enctype="multipart/form-data"
        class="new-form"
    >
        @csrf
        @if(isset($animal))
            @method('PATCH')
        @endif

        <div>
            <label for="name">Name:</label><br>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="{{ old('name', $animal->name ?? '') }}" 
                required
            >
            @error('name')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="species">Species:</label><br>
            <select name="species" id="species" required>
                <option value="">-- Select Species --</option>
                @foreach(App\Helper\Helper::all() as $species => $isPredator)
                    <option 
                        value="{{ $species }}" 
                        {{ old('species', $animal->species ?? '') == $species ? 'selected' : '' }}
                    >
                        {{ $species }}
                    </option>
                @endforeach
            </select>
            @error('species')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="is_predator">Is Predator?</label><br>
            <select name="is_predator" id="is_predator" required>
            <option value="1" {{ old('is_predator', isset($animal) ? $animal->is_predator : null) == 1 ? 'selected' : '' }}>True</option>
            <option value="0" {{ old('is_predator', isset($animal) ? $animal->is_predator : null) == 0 ? 'selected' : '' }}>False</option>
        </select>
        @error('is_predator')
            <div style="color: red">{{ $message }}</div>
        @enderror
        </div>
        <div>
    <label for="born_at">Date and Time of Birth:</label><br>
    <input 
        type="datetime-local" 
        id="born_at" 
        name="born_at" 
        value="{{ isset($animal) ? \Carbon\Carbon::parse($animal->born_at)->format('Y-m-d\TH:i') : old('born_at') }}" 
        required
    >
    @error('born_at')
        <div style="color:red">{{ $message }}</div>
    @enderror
</div>

        <div>
            <label for="enclosure_id">Enclosure:</label><br>
            <select name="enclosure_id" id="enclosure_id" required>
                <option value="">-- Select Enclosure --</option>
                @foreach($enclosures as $enclosure)
                    <option 
                        value="{{ $enclosure->id }}" 
                        {{ old('enclosure_id', $animal->enclosure_id ?? '') == $enclosure->id ? 'selected' : '' }}
                    >
                        {{ $enclosure->name }}
                    </option>
                @endforeach
            </select>
            @error('enclosure_id')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="picture">Animal Image:</label><br>
            <input type="file" name="picture" id="picture" accept="picture/*">
            @error('picture')
                <div style="color:red">{{ $message }}</div>
            @enderror

            @if(isset($animal) && $animal->picture)
                <p>Current Image:</p>
                <img src="{{ asset('storage/' . $animal->picture) }}" alt="Animal Image" width="150">
            @endif
        </div>

        <br>
        <button type="submit">{{ isset($animal) ? 'Update' : 'Create' }} Animal</button>
        <a href="{{ route('home') }}">Cancel</a>
    </form>
@endsection
