@extends('layouts.layout')

@section('content')
    <h1>{{ isset($enclosure) ? 'Edit Enclosure' : 'Create New Enclosure' }}</h1>

    <form 
        action="{{ isset($enclosure) ? route('enclosures.update', $enclosure->id) : route('enclosures.store') }}" 
        method="POST"
        class="new-form"
    >
        @csrf
        @if(isset($enclosure))
            @method('PATCH')  <!-- Use PATCH for updating -->
        @endif

        <!-- Enclosure Name -->
        <div>
            <label for="name">Enclosure Name:</label><br>
            <input 
                type="text" 
                name="name" 
                id="name" 
                value="{{ old('name', $enclosure->name ?? '') }}" 
                required
            >
            @error('name')
                <div style="color: red">{{ $message }}</div>
            @enderror
        </div>

        <!-- Animal Limit -->
        <div>
            <label for="limit">Animal Limit:</label><br>
            <input 
                type="number" 
                name="limit" 
                id="limit" 
                min="1" 
                value="{{ old('limit', $enclosure->limit ?? '') }}" 
                required
            >
            @error('limit')
                <div style="color: red">{{ $message }}</div>
            @enderror
        </div>

        <div>
    <label for="is_predator">Is Predator?</label><br>
    <select name="is_predator" id="is_predator" required>
        <option value="1" {{ old('is_predator', isset($enclosure) ? $enclosure->is_predator : null) == 1 ? 'selected' : '' }}>True</option>
        <option value="0" {{ old('is_predator', isset($enclosure) ? $enclosure->is_predator : null) == 0 ? 'selected' : '' }}>False</option>
    </select>
    @error('is_predator')
        <div style="color: red">{{ $message }}</div>
    @enderror
</div>

<div>
            <label for="feeding_at">Feeding Time:</label><br>
            <input 
                type="time" 
                name="feeding_at" 
                id="feeding_at" 
                value="{{ old('feeding_at',  isset($enclosure) ? $enclosure->feeding_at : '') }}"
                required
            >
            @error('feeding_at')
                <div style="color: red">{{ $message }}</div>
            @enderror
        </div>


        <div>
    <label for="users">Assign Users:</label><br>
    @foreach ($users as $user)
        <div>
            <input 
                type="checkbox" 
                name="users[]" 
                id="user_{{ $user->id }}" 
                value="{{ $user->id }}" 
                {{ isset($enclosure) && $enclosure->users->contains($user->id) ? 'checked' : '' }}
            >
            <label for="user_{{ $user->id }}">{{ $user->name }}</label>
        </div>
    @endforeach
    @error('users')
        <div style="color: red">{{ $message }}</div>
    @enderror
</div>

        <br>
        <button type="submit">
            {{ isset($enclosure) ? 'Update' : 'Create' }} Enclosure
        </button>
        <a href="{{ route('enclosures.index') }}">Cancel</a>
    </form>
@endsection
