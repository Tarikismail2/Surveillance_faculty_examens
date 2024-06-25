<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter une Salle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form method="POST" action="{{ route('salles.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 dark:text-gray-300">Nom</label>
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                    </div>

                    <div class="mb-4">
                        <label for="capacite" class="block text-gray-700 dark:text-gray-300">Capacité</label>
                        <x-input id="capacite" class="block mt-1 w-full" type="number" name="capacite" :value="old('capacite')" required />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-button>
                            {{ __('Ajouter') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
