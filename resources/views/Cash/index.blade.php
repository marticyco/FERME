@extends('adminlte::page')

@section('title', 'Cash Flow ')

@section('content_header')
    <h1>Cash Flow </h1>
@stop

@section('content')
    <div id="app">
        <!-- Utilisation du composant Vue.js pour la gestion des abonnements -->
        <cash-manager></cash-manager>
    </div>
@stop

@section('js')
    <!-- Inclure le script de Vue.js via Vite -->
    @vite('resources/js/app.js')
@stop

@section('css')
    <!-- Inclure les styles CSS via Vite -->
    @vite('resources/css/'tailwind.css)
@stop
