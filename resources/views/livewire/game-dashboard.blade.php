<div class="p-6 max-w-4xl mx-auto bg-gray-900 text-white rounded-xl shadow-md space-y-6">
    <header class="flex justify-between items-center border-b border-gray-700 pb-4">
        <h1 class="text-2xl font-bold">Colonie : {{ $planet->name }}</h1>
        <span class="text-sm bg-blue-600 px-3 py-1 rounded-full uppercase">{{ $planet->type }}</span>
    </header>

    <!-- Affichage des Ressources -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-gray-800 p-4 rounded-lg">
            <h3 class="text-gray-400 text-sm">Fer Disponible</h3>
            <p class="text-3xl font-mono font-bold text-yellow-400">
                {{ number_format($planet->iron, 2, ',', ' ') }}
            </p>
        </div>
        <div class="bg-gray-800 p-4 rounded-lg">
            <h3 class="text-gray-400 text-sm">Énergie</h3>
            <p class="text-3xl font-mono font-bold text-blue-400">
                {{ number_format($planet->energy, 2, ',', ' ') }}
            </p>
        </div>
    </div>

    <!-- Section Bâtiments / Actions -->
    <div class="bg-gray-800 p-4 rounded-lg">
        <h2 class="text-lg font-semibold mb-3">Centre de Commandement</h2>
        <p class="text-gray-400 text-sm">Profondeur actuelle de forage : <span class="text-white font-bold">{{ $planet->depth }} m</span></p>
    </div>
</div>