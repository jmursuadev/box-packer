<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Box Checker</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite('src/resources/ts/main.ts', 'vendor/jmursuadev/boxpacker')
    @stack('styles')
</head>

<body class="font-sans antialiased w-full">
    @yield('content')

    @stack('scripts')
</body>

</html>