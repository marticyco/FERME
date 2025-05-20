<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ma Ferme')</title>
    @vite(['resources/css/tailwind.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div id="app">
        <!-- Vue va monter ici ton Layout.vue avec <router-view /> -->
    </div>
</body>
</html>
