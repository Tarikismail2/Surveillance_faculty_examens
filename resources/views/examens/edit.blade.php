<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                @lang('Modifier l\'examen')
            </h2>
        </div>
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

                <form action="{{ route('examens.update', $examen->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <!-- Date -->
                        <div class="form-group">
                            <label for="date"
                                class="block text-gray-700 dark:text-gray-300">@lang('Date')</label>
                            <input type="date" name="date" id="date" value="{{ old('date', $examen->date) }}"
                                class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                            @error('date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Filière -->
                        <div class="form-group">
                            <label for="filiere"
                                class="block text-gray-700 dark:text-gray-300">@lang('Filière')</label>
                            <select
                                class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                id="filiere" name="code_etape" required>
                                <option value="">@lang('Sélectionnez une filière')</option>
                                <!-- New Filière -->
                                <optgroup label="@lang('Nouveaux Filières')">
                                    @foreach ($filieres as $filiere)
                                        @if ($filiere->type === 'new')
                                            <option value="{{ $filiere->code_etape }}"
                                                {{ old('code_etape', $code) == $filiere->code_etape ? 'selected' : '' }}>
                                                {{ $filiere->version_etape }}
                                            </option>
                                        @endif
                                    @endforeach
                                </optgroup>

                                <!-- Normal Filière -->
                                <optgroup label="@lang('Filières Normales')">
                                    @foreach ($filieres as $filiere)
                                        @if ($filiere->type === 'old')
                                            <option value="{{ $filiere->code_etape }}"
                                                {{ old('code_etape', $firstModuleCodeEtape) == $filiere->code_etape ? 'selected' : '' }}>
                                                {{ $filiere->version_etape }}
                                            </option>
                                        @endif
                                    @endforeach
                                </optgroup>

                            </select>
                            @error('code_etape')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Heure de Début -->
                        <div class="form-group">
                            <label for="heure_debut"
                                class="block text-gray-700 dark:text-gray-300">@lang('Heure de Début')</label>
                            <select name="heure_debut" id="heure_debut"
                                class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                                <option value="">@lang('Sélectionnez une heure de début')</option>
                                <option value="08:30"
                                    {{ old('heure_debut', $examen->heure_debut) == '08:30' ? 'selected' : '' }}>08:30
                                </option>
                                <option value="10:15"
                                    {{ old('heure_debut', $examen->heure_debut) == '10:15' ? 'selected' : '' }}>10:15
                                </option>
                                <option value="14:30"
                                    {{ old('heure_debut', $examen->heure_debut) == '14:30' ? 'selected' : '' }}>14:30
                                </option>
                                <option value="16:15"
                                    {{ old('heure_debut', $examen->heure_debut) == '16:15' ? 'selected' : '' }}>16:15
                                </option>
                            </select>
                            @error('heure_debut')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Module -->
                        <div class="form-group">
                            <label for="module"
                                class="block text-gray-700 dark:text-gray-300">@lang('Module')</label>
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

                        <!-- Heure de Fin -->
                        <div class="form-group">
                            <label for="heure_fin"
                                class="block text-gray-700 dark:text-gray-300">@lang('Heure de Fin')</label>
                            <select name="heure_fin" id="heure_fin"
                                class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                                <option value="">@lang('Sélectionnez une heure de fin')</option>
                                <option value="10:00"
                                    {{ old('heure_fin', $examen->heure_fin) == '10:00' ? 'selected' : '' }}>10:00
                                </option>
                                <option value="11:45"
                                    {{ old('heure_fin', $examen->heure_fin) == '11:45' ? 'selected' : '' }}>11:45
                                </option>
                                <option value="16:00"
                                    {{ old('heure_fin', $examen->heure_fin) == '16:00' ? 'selected' : '' }}>16:00
                                </option>
                                <option value="17:45"
                                    {{ old('heure_fin', $examen->heure_fin) == '17:45' ? 'selected' : '' }}>16:45
                                </option>
                            </select>
                            @error('heure_fin')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Enseignant -->
                        <div class="form-group">
                            <label for="id_enseignant"
                                class="block text-gray-700 dark:text-gray-300">@lang('Enseignant')</label>
                            <select name="id_enseignant" id="id_enseignant"
                                class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">@lang('Choisir un enseignant')</option>
                                @foreach ($enseignants as $enseignant)
                                    <option value="{{ $enseignant->id }}"
                                        {{ old('id_enseignant', $examen->id_enseignant) == $enseignant->id ? 'selected' : '' }}>
                                        {{ $enseignant->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_enseignant')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Session -->
                        <div class="form-group">
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

                        <!-- Salles supplémentaires -->
<div id="additional-rooms" class="space-y-4">
    @foreach ($additionalSalles as $index => $salleId)
        @php
            $salle = $salles->firstWhere('id', $salleId);
        @endphp
        <div class="flex items-center space-x-4 p-4 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800">
            <div class="w-full">
                <label for="additional_salles[{{ $index }}]" class="block text-gray-700 dark:text-gray-300 text-sm font-medium">
                    @lang('Salle Supplémentaire')
                </label>
                <select name="additional_salles[{{ $index }}]" id="additional_salles[{{ $index }}]"
                    class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm additional-salle-select">
                    <option value="">@lang('Choisir une salle')</option>
                    @foreach ($salles as $salleOption)
                        <option value="{{ $salleOption->id }}" {{ $salleOption->id == $salle->id ? 'selected' : '' }} data-capacite="{{ $salleOption->capacite }}">
                            {{ $salleOption->name }} (Capacité: {{ $salleOption->capacite }})
                        </option>
                    @endforeach
                </select>
                <p class="text-red-500 text-xs mt-1"></p>
            </div>
            <button type="button" class="remove-room bg-red-500 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 hover:bg-red-600 transition">
                @lang('Supprimer')
            </button>
        </div>
    @endforeach
</div>


                        <div class="form-group mt-6">
                            <button type="button" id="add-room-button"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                @lang('Ajouter une autre salle')
                            </button>
                        </div>

                        <div class="form-group mt-6">
                            <label for="inscriptions_count"
                                class="block text-gray-700 dark:text-gray-300 text-sm font-medium">@lang('Inscriptions')</label>
                            <input type="number" id="inscriptions_count" name="inscriptions_count"
                                class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                readonly>
                        </div>

                        <div class="form-group mt-6">
                            <label for="total_capacity" class="block text-gray-700 dark:text-gray-300">
                                @lang('Restes des Inscriptions')
                            </label>
                            <input type="number" id="total_capacity" name="total_capacity"
                                class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                readonly>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('examens.index', ['sessionId' => $selected_session->id]) }}"
                                class="inline-flex items-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md transition focus:outline-none focus:ring-2 focus:ring-gray-500">
                                <i class="fas fa-arrow-left mr-2"></i> @lang('Retour')
                            </a>
                            <button type="submit"
                                class="inline-flex items-center bg-green-500 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition focus:outline-none focus:ring-2 focus:ring-green-400">
                                <i class="fas fa-save mr-2"></i> @lang('Modifier Examen')
                            </button>
                        </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>

   document.addEventListener('DOMContentLoaded', () => {
    const departementSelect = document.getElementById('departement');
    const enseignantSelect = document.getElementById('enseignant');
    const filiereSelect = document.getElementById('filiere');
    const moduleSelect = document.getElementById('module');
    const salleSelect = document.getElementById('id_salle');
    const inscriptionsCount = document.getElementById('inscriptions_count');
    const remainingInscriptions = document.getElementById('remaining_inscriptions');
    const addSalleButton = document.getElementById('add_salle_button');
    const additionalSallesDiv = document.getElementById('additional_salles');
    const allocationModeSelect = document.getElementById('allocation_mode');
    const manualAllocationDiv = document.getElementById('manual_allocation');
    const automaticAllocationDiv = document.getElementById('automatic_allocation');
    const automaticAllocationSummary = document.getElementById('automatic_allocation_summary');
    const selectedSalles = new Set();

    // Récupérer les modules pour une filière
    filiereSelect.addEventListener('change', function () {
        const code_etape = this.value;
        moduleSelect.innerHTML = '<option value="">@lang('Sélectionnez un module')</option>';

        if (code_etape) {
            fetch(`/examens/getModulesByFiliere/${code_etape}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(module => {
                        const option = document.createElement('option');
                        option.value = module.lib_elp;
                        option.textContent = `${module.lib_elp} (${module.inscriptions_count} @lang('inscrits'))`;
                        option.setAttribute('data-inscriptions', module.inscriptions_count);
                        option.setAttribute('data-capacite', module.capacite);
                        moduleSelect.appendChild(option);
                    });
                    $('#module').trigger('change'); // Mettre à jour Select2 après le chargement
                })
                .catch(error => console.error('Error fetching modules:', error));
        }
    });

    // Mettre à jour le nombre d'inscriptions pour le module sélectionné
    moduleSelect.addEventListener('change', function () {
        const selectedModule = moduleSelect.options[moduleSelect.selectedIndex];
        const inscriptions = selectedModule.getAttribute('data-inscriptions') || 0;
        inscriptionsCount.value = inscriptions;
        updateRemainingInscriptions();
    });

    // Fonction pour mettre à jour les inscriptions restantes en fonction de la capacité
    function updateRemainingInscriptions() {
        let totalCapacity = 0;

        const mainSalleCapacity = salleSelect.options[salleSelect.selectedIndex]?.getAttribute('data-capacite');
        if (mainSalleCapacity) {
            totalCapacity += parseInt(mainSalleCapacity);
        }

        const additionalSalleSelects = additionalSallesDiv.querySelectorAll('select');
        additionalSalleSelects.forEach(salleSelect => {
            const capacity = salleSelect.options[salleSelect.selectedIndex]?.getAttribute('data-capacite');
            if (capacity) {
                totalCapacity += parseInt(capacity);
            }
        });

        const inscriptions = parseInt(inscriptionsCount.value) || 0;
        const remaining = inscriptions - totalCapacity;
        remainingInscriptions.value = remaining;
    }

    // Fonction pour filtrer les salles disponibles
    function getSelectedSalles() {
        const selectedSalles = [];
        const mainSalleValue = salleSelect.value;
        if (mainSalleValue) selectedSalles.push(mainSalleValue);

        const additionalSalleSelects = additionalSallesDiv.querySelectorAll('select');
        additionalSalleSelects.forEach(select => {
            if (select.value) selectedSalles.push(select.value);
        });

        return selectedSalles;
    }

    function filterAvailableSalles() {
        const selectedSalles = getSelectedSalles();

        // Désactiver les salles déjà sélectionnées dans la salle principale
        Array.from(salleSelect.options).forEach(option => {
            option.disabled = selectedSalles.includes(option.value) && option.value !== salleSelect.value;
        });

        // Désactiver les salles déjà sélectionnées dans les salles additionnelles
        const additionalSalleSelects = additionalSallesDiv.querySelectorAll('select');
        additionalSalleSelects.forEach(select => {
            Array.from(select.options).forEach(option => {
                option.disabled = selectedSalles.includes(option.value) && option.value !== select.value;
            });
        });
    }

    salleSelect.addEventListener('change', function () {
        filterAvailableSalles();
        updateRemainingInscriptions();
    });

    addSalleButton.addEventListener('click', function () {
        const salleCount = additionalSallesDiv.children.length;
        const newSalleDiv = document.createElement('div');
        newSalleDiv.className = 'mt-2 flex items-center';

        const newSalleSelect = salleSelect.cloneNode(true); // Cloner le select de salle
        newSalleSelect.name = `additional_salles[${salleCount}]`;
        newSalleSelect.id = `additional_salle_${salleCount}`;

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.innerText = '@lang('Supprimer')';
        removeButton.className = 'ml-2 py-1 px-2 bg-red-500 hover:bg-red-700 text-white font-semibold rounded-md shadow-md';
        removeButton.addEventListener('click', function () {
            additionalSallesDiv.removeChild(newSalleDiv);
            filterAvailableSalles(); // Correction ici
            updateRemainingInscriptions(); // Mise à jour après suppression
        });

        newSalleDiv.appendChild(newSalleSelect);
        newSalleDiv.appendChild(removeButton);
        additionalSallesDiv.appendChild(newSalleDiv);

        // Appliquer Select2 après l'ajout de la nouvelle salle
        $(newSalleSelect).select2({
            placeholder: "@lang('Choisir une salle')",
            allowClear: true
        });

        newSalleSelect.addEventListener('change', function () {
            filterAvailableSalles();
            updateRemainingInscriptions();
        });

        filterAvailableSalles(); // Appliquer les restrictions après l'ajout d'une nouvelle salle
    });

    filterAvailableSalles();

    // Gestion du mode d'affectation (manuel ou automatique)
    allocationModeSelect.addEventListener('change', function () {
        if (this.value === 'manual') {
            manualAllocationDiv.classList.remove('hidden');
            automaticAllocationDiv.classList.add('hidden');
        } else {
            manualAllocationDiv.classList.add('hidden');
            automaticAllocationDiv.classList.remove('hidden');
        }
    });

    // Initialisation de l'affichage
    allocationModeSelect.dispatchEvent(new Event('change'));
});

</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Script pour activer Select2 -->
<script>
    $(document).ready(function() {
        // Appliquer Select2 à la liste déroulante des enseignants
        $('#id_enseignant').select2({
            placeholder: "@lang('Choisir un enseignant')", // Placeholder par défaut
            allowClear: true // Permet de désélectionner
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Appliquer Select2 pour la filière
        $('#filiere').select2({
            placeholder: "@lang('Sélectionnez une filière')",
            allowClear: true
        });

        // Appliquer Select2 pour le module
        $('#module').select2({
            placeholder: "@lang('Sélectionnez un module')",
            allowClear: true
        });

        // Lorsque la filière change
        $('#filiere').on('change', function() {
            const code_etape = $(this).val();
            $('#module').empty().append(
                '<option value="">@lang('Sélectionnez un module')</option>'
            ); // Réinitialiser le sélecteur de modules

            if (code_etape) {
                fetch(`/examens/getModulesByFiliere/${code_etape}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(module => {
                            // Ajouter les modules avec le nombre d'inscriptions
                            $('#module').append(
                                `<option value="${module.lib_elp}" data-inscriptions="${module.inscriptions_count}">${module.lib_elp} (${module.inscriptions_count} @lang('inscrits'))</option>`
                            );
                        });
                        $('#module').trigger('change'); // Mettre à jour Select2
                    })
                    .catch(error => console.error('Error fetching modules:', error));
            }
        });

        // Lorsque le module change
        $('#module').on('change', function() {
            const selectedModule = $(this).find(':selected');
            const inscriptionsCount = selectedModule.data('inscriptions') ||
                0; // Récupérer les inscriptions

            // Mettre à jour la valeur du champ caché
            $('#inscriptions_count').val(inscriptionsCount);
        });

        // Appliquer Select2 pour les autres champs (si nécessaire)
        $(document).ready(function() {
    // Initialiser Select2 sur les champs de salles supplémentaires
    $('.additional-salle-select').select2({
        placeholder: "@lang('Sélectionnez une salle')",
        allowClear: true,
        theme: "classic"
    });

    // Initialisation de Select2 pour les nouvelles salles ajoutées dynamiquement
    $('#add-room-button').on('click', function() {
        // Ajouter une nouvelle salle via JS (vous avez déjà le code pour ça)
        // Initialiser Select2 sur le nouveau champ ajouté
        $('.additional-salle-select').last().select2({
            placeholder: "@lang('Sélectionnez une salle')",
            allowClear: true,
            theme: "classic"
        });
    });
});

        $('#id_enseignant').select2({
            placeholder: "@lang('Choisir un enseignant')",
            allowClear: true
        });
    });
</script>
