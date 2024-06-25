<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier la Salle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form method="POST" action="{{ route('salles.update', $salle->id) }}">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name" class="block text-gray-700 dark:text-gray-300">Nom</label>
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="$salle->name" required autofocus />
                    </div>

                    <div class="mt-4">
                        <label for="capacite" class="block text-gray-700 dark:text-gray-300">Capacité</label>
                        <x-input id="capacite" class="block mt-1 w-full" type="number" name="capacite" :value="$salle->capacite" required />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-button class="ml-4">
                            {{ __('Mettre à jour') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
