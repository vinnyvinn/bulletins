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
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
            'appname' => config('app.name'),
            'user' => Auth::id()
        ]) !!};
    </script>
</head>
<body>
<div id="app">
    <core-nav v-if="isLoggedIn" app="{{ config('app.name') }}"></core-nav>
    <alert v-show="showAlert" :errors="errors" :level="level"></alert>
    <loader v-if="isLoading"></loader>

    <transition
        name="custom-classes-transition"
        enter-active-class="animated fadeIn"
    >
        <router-view></router-view>
    </transition>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
