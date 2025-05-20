@extends('adminlte::page')

@section('title', 'Gestion des Pontes ')

@section('content_header')
    <h1>Gestion des Incubations </h1>
@stop

@section('content')
    <div id="app">
        <!-- Utilisation du composant Vue.js pour la gestion des abonnements -->
        <ponte-manager></ponte-manager>
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
