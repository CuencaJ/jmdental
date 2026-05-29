<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JM Dental</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Vendor CSS --}}
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    {{-- Template CSS --}}
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
</head>

<body>

    {{-- HEADER --}}
    @include('layouts.partials.header')

    {{-- CONTENIDO DE LAS VISTAS --}}
    <main class="main">
        @yield('content')
    </main>

    {{-- FOOTER (opcional si lo tienes) --}}
    @includeIf('layouts.partials.footer')

    {{-- Vendor JS --}}
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

</body>
</html>