@extends('adminlte::page')

@section('title', 'Comptabilité Generale ')

@section('content_header')
    <h1>Gestion Comptable </h1>
@stop

@section('content')
    <!-- Le point de montage Vue.js -->
    <div id="app">
        <account></account>  <!-- Composant Vue.js -->
    </div>
@stop

@section('js')
    <!-- Inclure le script de Vue.js via Vite -->
    @vite('resources/js/app.js')
@stop

@section('css')
    <!-- Inclure les styles CSS via Vite -->
    @vite('resources/css/tailwind.css')
@stop