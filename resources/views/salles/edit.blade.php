<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
        <h2 class="font-semibold text-2xl text-black-600 leading-tight">
            {{ __('Modifier une Salle') }}
        </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form method="POST" action="{{ route('salles.update', $salle->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 dark:text-gray-300 text-lg font-semibold">Nom</label>
                        <x-input id="name" class="block mt-1 w-full border-blue-600 focus:ring focus:ring-blue-300 rounded-md" type="text" name="name" value="{{ $salle->name }}" required autofocus />
                    </div>

                    <div class="mb-4">
                        <label for="capacite" class="block text-gray-700 dark:text-gray-300 text-lg font-semibold">Capacité</label>
                        <x-input id="capacite" class="block mt-1 w-full border-blue-600 focus:ring focus:ring-blue-300 rounded-md" type="number" name="capacite" value="{{ $salle->capacite }}" required />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Modifier') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
