@extends('adminlte::page')

@section('title', 'Se connecter')

@section('content_header')
    <div class="text-center mt-4">
        <h1 class="text-3xl font-bold text-yellow-600 animate__animated animate__fadeInDown">
            🐓 Bienvenue sur <span class="text-red-600">Africa Volaille d’OR</span>
        </h1>
        <p class="text-sm text-gray-600 mt-1 font-semibold">Veuillez vous authentifier pour accéder à votre espace.</p>
    </div>
@stop

@section('content')
    <!-- Point de montage Vue.js -->
    <div id="app" class="mt-8">
        <auth></auth>
    </div>

    <!-- Slogan animé -->
    <div class="text-center mt-12">
        <div class="inline-block px-6 py-2 bg-green-50 border border-green-200 rounded-lg shadow-sm animate__animated animate__fadeInUp">
            <p class="text-green-700 text-sm font-medium">
                🌿 <em>Nous vous offrons un mode de vie sain</em>
            </p>
        </div>
    </div>
@stop

@section('js')
    @vite('resources/js/app.js')
@stop

@section('css')
    @vite('resources/css/tailwind.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@stop
