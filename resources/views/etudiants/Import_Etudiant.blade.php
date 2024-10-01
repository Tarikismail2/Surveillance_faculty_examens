<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Module :') }} {{$module->lib_elp}} 
            </h2>
            <a href="{{ route('sessions.index') }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6">
                <h2 class="text-3xl font-semibold mb-6 text-gray-800">{{ __('Importer un fichier Excel qui contient les etudiants') }}</h2>
              
                <!-- Formulaire d'importation -->
                <form id="import-form" action="{{ route('import.store', ['id_session' => $module->id_session, 'id_module' => $module->id, 'code_etape' => $module->code_etape]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Sélecteur de fichier -->
                    <div class="mb-4">
                        <label for="file"
                            class="block text-sm font-medium text-gray-700">{{ __('Choisir un fichier Excel') }}</label>
                        <input type="file" name="file" id="file" accept=".xlsx, .xls"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    </div>

                    <div class="flex space-x-4">
                        <button id="import-button" type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-file-import mr-2"></i> {{ __('Importer') }}
                        </button>
                        <button id="cancel-button" type="button"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-times-circle mr-2"></i> {{ __('Arrêter') }}
                        </button>
                    </div>
                </form>

                <!-- Animation de chargement -->
                <div id="loading-spinner" class="mt-4 hidden flex items-center justify-center">
                    <div class="spinner mr-2"></div>
                    <span class="text-blue-600">{{ __('Le fichier est en cours de traitement. Veuillez patienter.') }}</span>
                </div>

                <!-- Messages de succès et d'erreur -->
                @if (session('success'))
                    <div class="mt-4 text-green-600">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="mt-4">
                        <ul class="list-disc list-inside text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Inclure JavaScript pour gérer l'importation et l'arrêt -->
    <script>
        document.getElementById('import-form').addEventListener('submit', function() {
            document.getElementById('import-button').disabled = true; // Désactiver le bouton pour éviter les clics multiples
            document.getElementById('loading-spinner').classList.remove('hidden'); // Afficher l'animation de chargement
        });

        document.getElementById('cancel-button').addEventListener('click', function() {
            fetch('{{ route('import.cancel') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({}) // Envoi de données vides pour la requête POST
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'cancelled') {
                    document.getElementById('import-button').disabled = false; // Réactiver le bouton d'importation
                    document.getElementById('loading-spinner').classList.add('hidden'); // Masquer l'animation de chargement
                    alert('Importation annulée');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'annulation de l\'importation.');
            });
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
