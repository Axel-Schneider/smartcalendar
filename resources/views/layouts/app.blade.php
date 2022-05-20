<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" integrity="sha512-Oy+sz5W86PK0ZIkawrG0iv7XwWhYecM3exvUtMKNJMekGFJtVAhibhRPTpmyTj8+lJCkmWfnpxKgT2OopquBHA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div>

        <!-- Page Heading -->
        <header class="h-2/6">
            @include('layouts.navigation')
        </header>

        <!-- Page Content -->
        <main class="h-4/6">
            @yield('content')
        </main>
    </div>
    @yield('popup')
    <script>
        window.user = {
                id: {{ Auth::user()->id }},
                name: "{{ Auth::user()->name }}"
            };
    </script>
    @yield('script')
    <!-- Scripts -->
    <script src="{{asset('js/core/libraries/jquery.min.js')}}" type="text/javascript" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        let logoText = document.getElementById("logo-day");
        logoText.innerHTML = new Date().getDate().toLocaleString('local', {
            minimumIntegerDigits: 2
        });
    </script>
    <script src="../path/to/flowbite/dist/flowbite.js"></script>
</body>

</html>