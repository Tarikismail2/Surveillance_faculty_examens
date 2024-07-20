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
                        <input type="date" name="date" id="date" value="{{ old('date') }}"
                            class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                        @error('date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="heure_debut"
                            class="block text-gray-700 dark:text-gray-300">@lang('Heure de Début')</label>
                        <input type="time" name="heure_debut" id="heure_debut" value="{{ old('heure_debut') }}"
                            class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                        @error('heure_debut')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="heure_fin" class="block text-gray-700 dark:text-gray-300">@lang('Heure de Fin')</label>
                        <input type="time" name="heure_fin" id="heure_fin" value="{{ old('heure_fin') }}"
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
                                <option value="{{ $filiere->version_etape }}" {{ old('id_filiere') == $filiere->version_etape ? 'selected' : ''}}>
                                    {{ $filiere->version_etape }}</option>
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

                    {{-- <div class="form-group mb-4">
                        <label for="departement"
                            class="block text-gray-700 dark:text-gray-300">@lang('Département')</label>
                        <select
                            class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            id="departement" name="departement" required>
                            <option value="">@lang('Sélectionnez un département')</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('departement')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div> --}}

                    <div class="form-group mb-4">
                        <label for="id_enseignant"
                            class="block text-gray-700 dark:text-gray-300">@lang('Enseignant')</label>
                        <select name="id_enseignant" id="id_enseignant"
                            class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">@lang('Choisir un enseignant')</option>
                            @foreach ($enseignants as $enseignant)
                                <option value="{{ $enseignant->id }}" {{ old('id_enseignant') == $enseignant->id ? 'selected' : '' }}>{{ $enseignant->name }}</option>
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
                                <option value="{{ $salle->id }}" data-capacite="{{ $salle->capacite }}" {{ old('id_salle') == $salle->id ? 'selected' : '' }}
                                    data-capacite="{{ $salle->capacite }}">
                                    {{ $salle->name }} (Capacité: {{ $salle->capacite }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_salle')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="additional_salles"
                            class="block text-gray-700 dark:text-gray-300">@lang('Salles Additionnelles')</label>
                        <div id="additional_salles">
                            <!-- Les salles additionnelles seront ajoutées ici -->
                        </div>
                        <button type="button" id="add_salle_button"
                            class="mt-2 py-2 px-4 bg-blue-500 hover:bg-blue-700 text-white font-semibold rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                            @lang('Ajouter une salle')
                        </button>
                    </div>

                    <div class="form-group mb-4">
                        <label for="inscriptions_count"
                            class="block text-gray-700 dark:text-gray-300">@lang('Inscriptions')</label>
                        <input type="number" id="inscriptions_count" name="inscriptions_count"
                            class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            readonly>
                    </div>

                    <div class="form-group mb-4">
                        <label for="total_capacity"
                            class="block text-gray-700 dark:text-gray-300">@lang('Capacité Totale')</label>
                        <input type="number" id="total_capacity" name="total_capacity"
                            class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            readonly>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('examens.index', ['sessionId' => $selected_session->id]) }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> @lang('Retour')
                        </a>
                        <button type="submit"
                            class="py-2 px-4 bg-green-500 hover:bg-green-700 text-white font-semibold rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-75">
                            <i class="fas fa-save mr-2"></i> @lang('Créer Examen')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const departementSelect = document.getElementById('departement');
            const enseignantSelect = document.getElementById('enseignant');
            const filiereSelect = document.getElementById('filiere');
            const moduleSelect = document.getElementById('module');
            const salleSelect = document.getElementById('id_salle');
            const inscriptionsCount = document.getElementById('inscriptions_count');
            const totalCapacity = document.getElementById('total_capacity');
            const addSalleButton = document.getElementById('add_salle_button');
            const additionalSallesDiv = document.getElementById('additional_salles');

            // departementSelect.addEventListener('change', function() {
            //     const departmentId = this.value;
            //     enseignantSelect.innerHTML = '<option value="">@lang('Choisir un enseignant')</option>';

            //     if (departmentId) {
            //         fetch(`/examens/getEnseignantsByDepartment/${departmentId}`)
            //             .then(response => response.json())
            //             .then(data => {
            //                 data.forEach(enseignant => {
            //                     const option = document.createElement('option');
            //                     option.value = enseignant.id;
            //                     option.textContent = `${enseignant.name} ${enseignant.email}`;
            //                     enseignantSelect.appendChild(option);
            //                 });
            //             })
            //             .catch(error => console.error('Error fetching enseignants:', error));
            //     }
            // });



            filiereSelect.addEventListener('change', function() {
                const filiereId = this.value;
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
                                option.setAttribute('data-inscriptions', module
                                    .inscriptions_count);
                                moduleSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching modules:', error));
                }
            });

            moduleSelect.addEventListener('change', function() {
                const selectedModule = moduleSelect.options[moduleSelect.selectedIndex];
                const inscriptions = selectedModule.getAttribute('data-inscriptions') || 0;
                inscriptionsCount.value = inscriptions;
                updateTotalCapacity();
            });

            salleSelect.addEventListener('change', function() {
                updateTotalCapacity();
            });

            addSalleButton.addEventListener('click', function() {
                const salleCount = additionalSallesDiv.children.length;
                const newSalleDiv = document.createElement('div');
                newSalleDiv.className = 'mt-2 flex items-center';

                const newSalleSelect = salleSelect.cloneNode(true);
                newSalleSelect.name = `additional_salles[${salleCount}]`;
                newSalleSelect.id = `additional_salle_${salleCount}`;
                newSalleSelect.addEventListener('change', function() {
                    updateTotalCapacity();
                });

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.innerText = '@lang('Supprimer')';
                removeButton.className =
                    'ml-2 py-1 px-2 bg-red-500 hover:bg-red-700 text-white font-semibold rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-75';
                removeButton.addEventListener('click', function() {
                    additionalSallesDiv.removeChild(newSalleDiv);
                    updateTotalCapacity();
                });

                newSalleDiv.appendChild(newSalleSelect);
                newSalleDiv.appendChild(removeButton);
                additionalSallesDiv.appendChild(newSalleDiv);
            });

            function updateTotalCapacity() {
                let total = 0;

                const mainSalleCapacity = salleSelect.options[salleSelect.selectedIndex]?.getAttribute(
                    'data-capacite');
                if (mainSalleCapacity) {
                    total += parseInt(mainSalleCapacity);
                }

                const additionalSalleSelects = additionalSallesDiv.querySelectorAll('select');
                additionalSalleSelects.forEach(salleSelect => {
                    const capacity = salleSelect.options[salleSelect.selectedIndex]?.getAttribute(
                        'data-capacite');
                    if (capacity) {
                        total += parseInt(capacity);
                    }
                });

                totalCapacity.value = total;
            }
        });
    </script>
</x-app-layout>
