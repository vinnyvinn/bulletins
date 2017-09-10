<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
            'appname' => config('app.name'),
            'user' => Auth::id(),
            'station_id' => session('station_id', 0),
            'contract_id' => session('contract_id', 0)
        ]) !!};
    </script>
</head>
<body>
<div id="app">
    <alert v-show="showAlert" :errors="errors" :level="level"></alert>
    <loader v-if="isLoading"></loader>
    <transport-nav v-if="isLoggedIn" app="{{ config('app.name') }}"></transport-nav>

    <div class="main-content">
        <transition
                name="custom-classes-transition"
                enter-active-class="animated fadeIn"
        >
            <router-view></router-view>
        </transition>
    </div>

</div>

<!-- Scripts -->
<script src="{{ asset(mix('js/app.js')) }}"></script>
</body>
</html>
