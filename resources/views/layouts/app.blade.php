<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon icon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('/images/favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer>
    </script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Icons -->
    <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toastr.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

</head>
<body>
    <div id="app">
        <header class="header-two h-two-h bg-top">
            @include('layouts.header')
        </header>
        @include('layouts.nav')
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    @if (session()->has('success'))
    <script>
        const notyf = new Notyf({dismissible: true})
        notyf.success('{{ session('success') }}')
    </script>
    @endif

    @if(session()->has('error'))
    <script>
        const notyf = new Notyf({
            dismissible: true,
            duration: 5000,
            position: {
                x: 'right',
                y: 'top',
            }
        })
        notyf.error('{{ session('error') }}')
    </script>
    @endif

    <script>
    function add(id) {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '/api/confirm-appoinment?id='+id,
            data: {'id': id},
            success: function(data){
                const notyf = new Notyf({dismissible: true})
                notyf.success('Appoinment Confirmed !')
                setTimeout(function(){
                    window.location.reload(); }
                , 1000);

            }
        });
    }
    </script>
    <footer class="footer">
        @include('layouts.footer')
    </footer>
</body>
</html>
