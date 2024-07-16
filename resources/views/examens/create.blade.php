<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @lang('Créer un nouvel examen')
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4">
                        <div class="font-medium text-red-600">@lang('Whoops! Quelque chose s\'est mal passé.')</div>
                        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-4">
                        <ul class="mt-3 list-disc list-inside text-sm text-green-600">
                            @foreach (session('success') as $successful)
                                <li>{{ $successful }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('examens.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-4">
                        <label for="date" class="block text-gray-700 dark:text-gray-300">@lang('Date')</label>
                        <input type="date" name="date" id="date"
                            class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                        @error('date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="heure_debut"
                            class="block text-gray-700 dark:text-gray-300">@lang('Heure de Début')</label>
                        <input type="time" name="heure_debut" id="heure_debut"
                            class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                        @error('heure_debut')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="heure_fin" class="block text-gray-700 dark:text-gray-300">@lang('Heure de Fin')</label>
                        <input type="time" name="heure_fin" id="heure_fin"
                            class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                        @error('heure_fin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="filiere" class="block text-gray-700 dark:text-gray-300">@lang('Filière')</label>
                        <select
                            class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            id="filiere" name="id_filiere" required>
                            <option value="">@lang('Sélectionnez une filière')</option>
                            @foreach ($filieres as $filiere)
                                <option value="{{ $filiere->version_etape }}">{{ $filiere->version_etape }}</option>
                            @endforeach
                        </select>
                        @error('id_filiere')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="module" class="block text-gray-700 dark:text-gray-300">@lang('Module')</label>
                        <select
                            class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            id="module" name="id_module" required>
                            <option value="">@lang('Sélectionnez un module')</option>
                            <!-- Les modules seront remplis dynamiquement -->
                        </select>
                        @error('id_module')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="id_enseignant"
                            class="block text-gray-700 dark:text-gray-300">@lang('Enseignant')</label>
                        <select name="id_enseignant" id="id_enseignant"
                            class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">@lang('Choisir un enseignant')</option>
                            @foreach ($enseignants as $enseignant)
                                <option value="{{ $enseignant->id }}">{{ $enseignant->name }}</option>
                            @endforeach
                        </select>
                        @error('id_enseignant')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="id_session"
                            class="block text-gray-700 dark:text-gray-300">@lang('Session')</label>
                        <select id="id_session" name="id_session"
                            class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            readonly>
                            <option value="{{ $selected_session->id }}">{{ $selected_session->type }}
                                ({{ $selected_session->date_debut }} - {{ $selected_session->date_fin }})</option>
                        </select>
                        @error('id_session')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="salle-section" class="form-group mb-4">
                        <label for="id_salle" class="block text-gray-700 dark:text-gray-300">@lang('Salle Principale')</label>
                        <select name="id_salle" id="id_salle"
                            class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">@lang('Choisir une salle')</option>
                            @foreach ($salles as $salle)
                                <option value="{{ $salle->id }}">{{ $salle->name }} ({{ $salle->capacite }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_salle')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="additional-rooms"></div>

                    <div class="form-group mb-4">
                        <button type="button" id="add-room-button"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                            @lang('Ajouter une autre salle')
                        </button>
                    </div>
                    @if (isset($moduleSelect))
                    <div class="form-group mb-4">
                        <p>Inscrits initiaux dans le module : {{ $moduleSelect->inscriptions_count }}</p>
                        <p>Inscrits restants après la sélection des salles : <span id="inscrits_restants">{{ $moduleSelect->inscriptions_count }}</span></p>
                        <input type="hidden" id="nombre_inscrits" name="nombre_inscrits" value="{{ $moduleSelect->inscriptions_count }}">
                    </div>
                @endif

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('examens.index') }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> @lang('Retour')
                        </a>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                            <i class="fas fa-save mr-2"></i> @lang('Enregistrer')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.getElementById('filiere').addEventListener('change', function() {
        const filiereId = this.value;
        const moduleSelect = document.getElementById('module');
        moduleSelect.innerHTML = '<option value="">@lang('Sélectionnez un module')</option>';

        if (filiereId) {
            fetch(`/examens/getModulesByFiliere/${filiereId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(module => {
                        const option = document.createElement('option');
                        option.value = module.id;
                        option.textContent =
                            `${module.lib_elp} (${module.inscriptions_count} @lang('inscrits'))`;
                        moduleSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching modules:', error));
        }
    });

    document.getElementById('add-room-button').addEventListener('click', function() {
        const additionalRooms = document.getElementById('additional-rooms');
        const salleSelect = document.createElement('div');
        salleSelect.className = 'flex mb-4';
        const select = document.createElement('select');
        select.name = 'additional_salles[]'; // Utilisation du même nom de champ pour les salles supplémentaires
        select.className =
            'form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm';
        select.innerHTML = `
            <option value="">@lang('Choisir une salle')</option>
            @foreach ($salles as $salle)
                <option value="{{ $salle->id }}">{{ $salle->name }} ({{ $salle->capacite }})</option>
            @endforeach
        `;

        const button = document.createElement('button');
        button.type = 'button';
        button.textContent = 'Supprimer';
        button.className = 'bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2';
        button.addEventListener('click', function() {
            additionalRooms.removeChild(salleSelect);
        });

        salleSelect.appendChild(select);
        salleSelect.appendChild(button);
        additionalRooms.appendChild(salleSelect);
    });


    function updateInscritsRestants() {
        const nombreInscritsInitial = parseInt(document.getElementById('nombre_inscrits').value);
        let capaciteTotaleSalles = 0;

        // Calcul de la capacité totale des salles sélectionnées
        const sallePrincipale = document.getElementById('id_salle');
        capaciteTotaleSalles += parseInt(sallePrincipale.options[sallePrincipale.selectedIndex].getAttribute('data-capacite'));

        const additionalRooms = document.querySelectorAll('[name="additional_salles[]"]');
        additionalRooms.forEach(room => {
            capaciteTotaleSalles += parseInt(room.options[room.selectedIndex].getAttribute('data-capacite'));
        });

        // Mise à jour de l'affichage
        const inscritsRestants = nombreInscritsInitial - capaciteTotaleSalles;
        document.getElementById('inscrits_restants').textContent = inscritsRestants;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Appel initial pour mettre à jour les inscrits restants
        updateInscritsRestants();

        // Écouteurs d'événements pour mettre à jour lors des changements de sélection de salle
        document.getElementById('id_salle').addEventListener('change', updateInscritsRestants);
        const additionalRooms = document.querySelectorAll('[name="additional_salles[]"]');
        additionalRooms.forEach(room => {
            room.addEventListener('change', updateInscritsRestants);
        });
    });
</script>
