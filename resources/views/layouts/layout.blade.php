<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
</head>
<body>
    @include('navigation.navigation')

    <main>
        @if(session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert error">
                {{ session('error') }}
            </div>
        @endif
        @yield('content')
</main>
</body>
</html>