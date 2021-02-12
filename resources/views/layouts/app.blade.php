<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    @stack('stylesheets_start')
    <link href="{{ asset('mdbootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('mdbootstrap/css/mdb.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('font-awesome/css/all.css') }}">  
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    @stack('stylesheets_end')
</head>
<style>
    @stack('styles')
</style>
@yield('body')
@stack('scripts_start')
<script src="{{ asset('jquery/jquery.min.js') }}"></script>
<script src="{{ asset('mdbootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('mdbootstrap/js/mdb.min.js') }}"></script>
@stack('scripts_end')
</html>
