<div class="p-6 max-w-4xl mx-auto bg-gray-900 text-white rounded-xl shadow-md space-y-6"
     x-data="{ currentIron: {{ $this->currentIron() }} }"
     x-on:sync-iron.window="currentIron = $event.detail.iron">
     
    <header class="flex justify-between items-center border-b border-gray-700 pb-4">
        <h1 class="text-2xl font-bold">Colony: {{ $planet->name }}</h1>
        <span class="text-sm bg-blue-600 px-3 py-1 rounded-full uppercase">{{ $planet->type }}</span>
    </header>

    <!-- Toast Notifications Flottantes -->
    <div x-data="{ 
            show: {{ session()->has('message') || session()->has('error') ? 'true' : 'false' }}, 
            message: '{{ session()->has('message') ? addslashes(session('message')) : (session()->has('error') ? addslashes(session('error')) : '') }}', 
            type: '{{ session()->has('error') ? 'error' : 'success' }}' 
         }"
         x-init="if(show) { setTimeout(() => show = false, 4000); }"
         class="fixed bottom-6 right-6 z-50 space-y-2">

        <template x-if="show">
            <div @click="show = false"
                 :class="type === 'success' ? 'bg-green-900 border-green-600 text-green-100' : 'bg-red-900 border-red-600 text-red-100'"
                 class="px-4 py-3 rounded-xl border shadow-2xl cursor-pointer flex items-center space-x-3 transition transform hover:scale-105">
                <span class="text-sm font-semibold" x-text="message"></span>
                <span class="text-xs opacity-60 ml-2">(cliquer pour fermer)</span>
            </div>
        </template>
    </div>

    <!-- Resources Display (ISOLÉ AVEC wire:ignore, qui émet sa valeur en temps réel vers le parent) -->
    <div class="grid grid-cols-2 gap-4" wire:ignore
         x-data="{
             base: {{ $this->currentIron() }},
             cps: {{ $ironPerSecond }},
             start: performance.now(),
             now: performance.now(),
             get iron() {
                 let val = this.base + this.cps * (this.now - this.start) / 1000;
                 // On met à jour la variable globale du parent pour les boutons
                 $dispatch('update-iron', { iron: val });
                 return val;
             },
             init() {
                 setInterval(() => { this.now = performance.now(); }, 100);
             }
         }"
         x-on:sync-iron.window="base = $event.detail.iron; start = performance.now(); now = start">

        <div class="bg-gray-800 p-4 rounded-lg">
            <h3 class="text-gray-400 text-sm">Available Iron</h3>
            <p class="text-3xl font-mono font-bold text-yellow-400"
               x-text="iron.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })">
            </p>
            <span class="text-xs text-gray-500">+{{ $ironPerSecond }} / sec</span>
        </div>
        <div class="bg-gray-800 p-4 rounded-lg">
            <h3 class="text-gray-400 text-sm">Energy</h3>
            <p class="text-3xl font-mono font-bold text-blue-400">
                {{ number_format($planet->energy, 2, '.', ',') }}
            </p>
        </div>
    </div>

    <!-- Buildings / Construction Section -->
    <div class="bg-gray-800 p-4 rounded-lg space-y-4"
         x-data="{ iron: {{ $this->currentIron() }} }"
         x-on:update-iron.window="iron = $event.detail.iron"
         x-on:sync-iron.window="iron = $event.detail.iron">
         
        <h2 class="text-lg font-semibold mb-3">Command Center & Construction</h2>
        
        <div class="grid grid-cols-1 gap-3">
            @foreach($buildings as $building)
                <div class="flex justify-between items-center bg-gray-900 p-3 rounded border border-gray-700">
                    <div>
                        <h4 class="font-bold text-yellow-500">{{ $building->name }}</h4>
                        <p class="text-xs text-gray-400">{{ $building->description }}</p>
                        <span class="text-xs text-yellow-300 font-mono">Cost: {{ $building->base_cost_iron }} Iron</span>
                    </div>
                    
                    <!-- Bouton réactif intelligent : se grise dynamiquement sans toucher au timer -->
                    <button wire:click="buyBuilding({{ $building->id }})" 
                            :disabled="iron < {{ $building->base_cost_iron }}"
                            :class="iron < {{ $building->base_cost_iron }} ? 'bg-gray-700 text-gray-500 cursor-not-allowed opacity-50' : 'bg-yellow-600 hover:bg-yellow-500 text-gray-950 font-bold'"
                            class="px-4 py-2 rounded text-sm transition">
                        Build
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</div>