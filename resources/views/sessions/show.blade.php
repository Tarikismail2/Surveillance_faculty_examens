<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Détails de la Session') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="ml-4 text-lg leading-7 font-semibold text-gray-800 dark:text-gray-200">
                                Session ID: {{ $session->id }}
                            </div>
                        </div>
                        <div class="flex items-center">
                            <a href="{{ route('sessions.index') }}"
                                class="inline-block bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 hover:text-gray-900 dark:hover:text-gray-100 rounded-md py-2 px-4 transition-colors duration-300 ease-in-out">
                                {{ __('Retour à la liste') }}
                            </a>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="ml-12 mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block font-semibold mb-1 text-gray-800 dark:text-gray-200">Type:</label>
                                <p class="text-gray-800 dark:text-gray-200">{{ $session->type }}</p>
                            </div>
                            <div>
                                <label class="block font-semibold mb-1 text-gray-800 dark:text-gray-200">Date de Début:</label>
                                <p class="text-gray-800 dark:text-gray-200">{{ $session->date_debut }}</p>
                            </div>
                            <div>
                                <label class="block font-semibold mb-1 text-gray-800 dark:text-gray-200">Date de Fin:</label>
                                <p class="text-gray-800 dark:text-gray-200">{{ $session->date_fin }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="flex flex-col md:flex-row gap-4 mt-8">
                        <a href="{{ route('examens.create', ['id' => $session->id]) }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out">
                            {{ __('Créer un examen') }}
                        </a>
                        <a href="{{ route('examens.index', ['id' => $session->id]) }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out">
                            {{ __('Voir les examens') }}
                        </a>
                        <a href="{{ route('import.form') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out">
                            {{ __('Importer le fichier excel') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
