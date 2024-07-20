<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Importation de Fichier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6">
                <h2 class="text-3xl font-semibold mb-6 text-gray-800">{{ __('Importer un fichier Excel') }}</h2>

                <!-- Formulaire d'importation -->
                <form id="import-form" action="{{ route('import.process') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Sélecteur de fichier -->
                    <div class="mb-4">
                        <label for="file" class="block text-sm font-medium text-gray-700">{{ __('Choisir un fichier Excel') }}</label>
                        <input type="file" name="file" id="file" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('file')
                            <div class="mt-2 text-red-600 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bouton d'importation -->
                    <div>
                        <button id="import-button" type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Importer') }}
                        </button>
                    </div>
                </form>

                <!-- Message de prétraitement -->
                <div id="preprocessing-message" class="mt-4 text-blue-600 hidden">
                    {{ __('Le fichier est en cours de traitement. Veuillez patienter.') }}
                </div>

                <!-- Animation de chargement -->
                <div id="loading-spinner" class="mt-4 hidden flex items-center justify-center">
                    <div class="spinner mr-2"></div>
                    <span class="text-blue-600">{{ __('Le fichier est en cours de traitement. Veuillez patienter.') }}</span>
                </div>

                <!-- Messages de succès et d'erreur -->
                @if(session('success'))
                    <div class="mt-4 text-green-600">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="mt-4">
                        <ul class="list-disc list-inside text-red-600">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Inclure JavaScript pour afficher le message de prétraitement et l'animation de chargement -->
    <script>
        document.getElementById('import-form').addEventListener('submit', function() {
            document.getElementById('import-button').disabled = true; // Désactiver le bouton pour éviter les clics multiples
            document.getElementById('preprocessing-message').classList.add('hidden'); // Cacher le message de prétraitement si affiché
            document.getElementById('loading-spinner').classList.remove('hidden'); // Afficher l'animation de chargement
        });
    </script>

    <!-- CSS pour l'animation de chargement -->
    <style>
        /* Animation de chargement */
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #3498db;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</x-app-layout>
