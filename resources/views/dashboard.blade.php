@extends('adminlte::page')

@section('title', 'Tableau de Bord')

@section('content_header')
    <h1>Tableau de Bord </h1>
@stop

@section('content')
    <!-- Le point de montage Vue.js -->
    <div id="app">
        <dashboard></dashboard>  <!-- Composant Vue.js -->
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
