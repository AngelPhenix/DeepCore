<div class="p-6 max-w-4xl mx-auto bg-gray-900 text-white rounded-xl shadow-md space-y-6"
     x-data="{ 
         iron: {{ $planet->iron }}, 
         cps: {{ $ironPerSecond }},
         init() {
             setInterval(() => {
                 this.iron += this.cps;
             }, 1000);
         }
     }">
     
    <header class="flex justify-between items-center border-b border-gray-700 pb-4">
        <h1 class="text-2xl font-bold">Colonie : {{ $planet->name }}</h1>
        <span class="text-sm bg-blue-600 px-3 py-1 rounded-full uppercase">{{ $planet->type }}</span>
    </header>

    <!-- Messages de notification -->
    @if (session()->has('message'))
        <div class="bg-green-800 text-green-100 p-3 rounded-lg text-sm">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-800 text-red-100 p-3 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Affichage des Ressources -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-gray-800 p-4 rounded-lg">
            <h3 class="text-gray-400 text-sm">Fer Disponible</h3>
            <p class="text-3xl font-mono font-bold text-yellow-400" 
               x-text="iron.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })">
                {{ number_format($planet->iron, 2, ',', ' ') }}
            </p>
            <span class="text-xs text-gray-500">+{{ $ironPerSecond }} / sec</span>
        </div>
        <div class="bg-gray-800 p-4 rounded-lg">
            <h3 class="text-gray-400 text-sm">Énergie</h3>
            <p class="text-3xl font-mono font-bold text-blue-400">
                {{ number_format($planet->energy, 2, ',', ' ') }}
            </p>
        </div>
    </div>

    <!-- Section Bâtiments / Achat -->
    <div class="bg-gray-800 p-4 rounded-lg space-y-4">
        <h2 class="text-lg font-semibold mb-3">Centre de Commandement & Construction</h2>
        
        <div class="grid grid-cols-1 gap-3">
            @foreach($buildings as $building)
                <div class="flex justify-between items-center bg-gray-900 p-3 rounded border border-gray-700">
                    <div>
                        <h4 class="font-bold text-yellow-500">{{ $building->name }}</h4>
                        <p class="text-xs text-gray-400">{{ $building->description }}</p>
                        <span class="text-xs text-yellow-300 font-mono">Coût : {{ $building->base_cost_iron }} Fer</span>
                    </div>
                    <button wire:click="buyBuilding({{ $building->id }})" 
                            class="bg-yellow-600 hover:bg-yellow-500 text-gray-950 font-bold px-4 py-2 rounded text-sm transition">
                        Construire
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</div>