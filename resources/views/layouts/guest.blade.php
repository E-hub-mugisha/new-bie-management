<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- styles -->

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- bootstrap -->
        <link href="{{ asset('assets/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    
        <!-- fontawesome -->
        <link href="{{ asset('assets/plugins/fontawesome/css/all.min.css')}}" rel="stylesheet">

        <!-- datatables -->
        <link href="{{ asset('assets/plugins/datatables/dataTables.min.css') }}" rel="stylesheet">


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col lg:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            

            <div class="mt-6 px-6 py-4 bg-white shadow-md">
                @yield('content')
            </div>
        </div>

        @include('includes.footer')
        
        <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables/dataTables.min.js') }}"></script>

        <script>
            $(document).ready(function() {
                $('#dataTable').DataTable();
            });
        </script>
    </body>
</html>
