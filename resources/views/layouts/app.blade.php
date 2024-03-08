<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Derak Trade Software Assistance - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body>
    <div class="container mx-auto px-4">
        @yield('content')
    </div>
    @yield('js')
</body>
</html>
