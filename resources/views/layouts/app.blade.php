<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deep Core - Idle Game</title>
    
    <!-- Tailwind CSS (via CDN pour aller vite sur le MVP, ou Vite si configuré) -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Styles Livewire -->
    @livewireStyles
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen flex flex-col justify-center items-center">

    <!-- C'est ici que le composant Livewire va s'afficher -->
    {{ $slot }}

    <!-- Scripts Livewire -->
    @livewireScripts
</body>
</html>