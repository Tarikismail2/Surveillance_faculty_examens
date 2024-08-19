<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-green-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-green-800 leading-tight">
                {{ __('Détails de la Session') }}
            </h2>
            <a href="{{ route('sessions.index') }}"
                class="inline-block bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 hover:text-gray-900 dark:hover:text-gray-100 rounded-md py-2 px-4 transition-colors duration-300 ease-in-out">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-center justify-center bg-gray-100 dark:bg-gray-700 p-4 rounded-md shadow-sm">
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Session ID:</div>
                            <p class="text-gray-800 dark:text-gray-200">{{ $session->id }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center bg-gray-100 dark:bg-gray-700 p-4 rounded-md shadow-sm">
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Type:</div>
                            <p class="text-gray-800 dark:text-gray-200">{{ $session->type }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center bg-gray-100 dark:bg-gray-700 p-4 rounded-md shadow-sm">
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Date de Début:</div>
                            <p class="text-gray-800 dark:text-gray-200">{{ $session->date_debut }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center bg-gray-100 dark:bg-gray-700 p-4 rounded-md shadow-sm">
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Date de Fin:</div>
                            <p class="text-gray-800 dark:text-gray-200">{{ $session->date_fin }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex flex-col md:flex-row gap-4">
                    <a href="{{ route('examens.create', ['id' => $session->id]) }}"
                        class="flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4H5a1 1 0 100 2h4v4a1 1 0 102 0v-4h4a1 1 0 100-2h-4V6z" clip-rule="evenodd" />
                        </svg>
                        {{ __('Créer un examen') }}
                    </a>
                    <a href="{{ route('examens.index', ['sessionId' => $session->id]) }}"
                        class="flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm2-10a1 1 0 00-2 0v2H8a1 1 0 000 2h2v2a1 1 0 002 0v-2h2a1 1 0 000-2h-2V8z" clip-rule="evenodd" />
                        </svg>
                        {{ __('Voir les examens') }}
                    </a>
                    <a href="{{ route('import.form', ['sessionId' => $session->id]) }}"
                        class="flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 3a7 7 0 100 14 7 7 0 000-14zM8 10a1 1 0 112 0v4a1 1 0 11-2 0v-4zm2-2a1 1 0 10-2 0v1a1 1 0 102 0V8z" />
                        </svg>
                        {{ __('Importer le fichier excel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
