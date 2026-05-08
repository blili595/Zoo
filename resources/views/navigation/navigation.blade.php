<nav style="display: flex; justify-content: space-between; align-items: center; padding: 10px;">
<div>
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('enclosures.index') }}">Enclosures</a>
        
    @auth 
    @if(auth()->user()->admin)

        <a href="{{ route('enclosures.create') }}">New Enclosure</a>
        <a href="{{ route('animals.create') }}">New Animal</a>
        <a href="{{ route('animals.archived') }}">Archived Animals</a>
            

    @endif
    @endauth

    @auth 
    <div class="logout">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>  
    </form>
    </div>
    @endauth
</div>

</nav>