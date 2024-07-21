<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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
                        <div class="font-medium text-green-600">@lang('Opération réussie!')</div>
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

                    <div class="form-group mb-4">
                        <label for="date" class="block text-gray-700 dark:text-gray-300">@lang('Date')</label>
                        <input type="date" name="date" id="date"
                            class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            value="{{ old('date', $examen->date) }}" required>
                        @error('date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="heure_debut"
                            class="block text-gray-700 dark:text-gray-300">@lang('Heure de Début')</label>
                        <input type="time" name="heure_debut" id="heure_debut"
                            class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            value="{{ $examen->heure_debut }}" required>
                        @error('heure_debut')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="heure_fin" class="block text-gray-700 dark:text-gray-300">@lang('Heure de Fin')</label>
                        <input type="time" name="heure_fin" id="heure_fin"
                            class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            value="{{ $examen->heure_fin }}" required>
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
                                <option value="{{ $filiere->version_etape }}"
                                    {{ old('id_filiere', optional($examen->module)->version_etape) == $filiere->version_etape ? 'selected' : '' }}>
                                    {{ $filiere->version_etape }}
                                </option>
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
                                <option value="{{ $enseignant->id }}"
                                    {{ old('id_enseignant', $examen->id_enseignant) == $enseignant->id ? 'selected' : '' }}>
                                    {{ $enseignant->name }}</option>
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

                    <div class="form-group mb-4">
                        <label for="id_salle" class="block text-gray-700 dark:text-gray-300">@lang('Salle Principale')</label>
                        <select name="id_salle" id="id_salle"
                            class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                            <option value="">@lang('Choisir une salle')</option>
                            @foreach ($salles as $salle)
                                <option value="{{ $salle->id }}" data-capacite="{{ $salle->capacite }}"
                                    {{ old('id_salle', $examen->id_salle) == $salle->id ? 'selected' : '' }}>
                                    {{ $salle->name }} (Capacité: {{ $salle->capacite }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_salle')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Salles supplémentaires -->
                    <div id="additional-rooms">
                        @foreach ($additionalSalles as $index => $salleId)
                            @php
                                $salle = $salles->firstWhere('id', $salleId);
                            @endphp
                            <div class="flex mb-4 additional-room">
                                <div class="w-5/6">
                                    <label for="additional_salles[{{ $index }}]"
                                        class="block text-gray-700 dark:text-gray-300">@lang('Salle Supplémentaire')</label>
                                    <select name="additional_salles[{{ $index }}]"
                                        id="additional_salles[{{ $index }}]"
                                        class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm additional-salle-select">
                                        <option value="">@lang('Choisir une salle')</option>
                                        @foreach ($salles as $salleOption)
                                            <option value="{{ $salleOption->id }}"
                                                {{ $salleOption->id == $salle->id ? 'selected' : '' }}
                                                data-capacite="{{ $salleOption->capacite }}">
                                                {{ $salleOption->name }} (Capacité: {{ $salleOption->capacite }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <button type="button"
                                    class="remove-room w-1/6 bg-red-500 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-7 ml-4">@lang('Supprimer')</button>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group mb-4">
                        <button type="button" id="add-room-button"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                            @lang('Ajouter une autre salle')
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
        const filiereSelect = document.getElementById('filiere');
        const moduleSelect = document.getElementById('module');
        const salleSelect = document.getElementById('id_salle');
        const inscriptionsCount = document.getElementById('inscriptions_count');
        const totalCapacity = document.getElementById('total_capacity');
        const addRoomButton = document.getElementById('add-room-button');
        const additionalRoomsDiv = document.getElementById('additional-rooms');

        function updateModuleOptions() {
            const filiereId = filiereSelect.value;
            moduleSelect.innerHTML = '<option value="">@lang('Sélectionnez un module')</option>';

            if (filiereId) {
                fetch(`/examens/getModulesByFiliere/${filiereId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Modules:', data); // Debugging: log fetched modules
                        data.forEach(module => {
                            const option = document.createElement('option');
                            option.value = module.id;
                            option.textContent =
                                `${module.lib_elp} (${module.inscriptions_count} @lang('inscrits'))`;
                            option.setAttribute('data-inscriptions', module.inscriptions_count);
                            moduleSelect.appendChild(option);
                            // Compare as numbers
                            if ({!! $examen->id_module !!} === module.id) {
                                option.selected = true;
                                console.log('Selected module:', module.id);
                                // Update inscriptions count and total capacity when module selected
                                inscriptionsCount.value = module.inscriptions_count;
                                updateTotalCapacity();
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching modules:', error));
            }
        }

        function updateTotalCapacity() {
            let total = 0;

            // Add capacity of the primary room
            const primaryRoom = salleSelect.options[salleSelect.selectedIndex];
            const primaryRoomCapacity = parseInt(primaryRoom.getAttribute('data-capacite')) || 0;
            total += primaryRoomCapacity;

            // Add capacity of additional rooms
            const additionalRoomSelects = additionalRoomsDiv.querySelectorAll('select.additional-salle-select');
            additionalRoomSelects.forEach(select => {
                const roomOption = select.options[select.selectedIndex];
                const roomCapacity = parseInt(roomOption.getAttribute('data-capacite')) || 0;
                total += roomCapacity;
            });

            totalCapacity.value = total;
        }

        function isRoomAlreadySelected(roomId) {
            const allSelectedRoomIds = [
                salleSelect.value,
                ...Array.from(additionalRoomsDiv.querySelectorAll('select.additional-salle-select'))
                .map(select => select.value)
            ];
            return allSelectedRoomIds.includes(roomId);
        }

        filiereSelect.addEventListener('change', updateModuleOptions);

        // Update module options on page load
        updateModuleOptions();

        moduleSelect.addEventListener('change', function() {
            const selectedModule = moduleSelect.options[moduleSelect.selectedIndex];
            const inscriptions = selectedModule.getAttribute('data-inscriptions') || 0;
            inscriptionsCount.value = inscriptions;
            updateTotalCapacity(); // Call a function to update total capacity
        });

        salleSelect.addEventListener('change', updateTotalCapacity);

        additionalRoomsDiv.addEventListener('change', function(event) {
            if (event.target.classList.contains('additional-salle-select')) {
                updateTotalCapacity();
            }
        });

        addRoomButton.addEventListener('click', function() {
            const roomCount = additionalRoomsDiv.children.length;

            const newRoomDiv = document.createElement('div');
            newRoomDiv.classList.add('flex', 'mb-4', 'additional-room');

            const newRoomSelectDiv = document.createElement('div');
            newRoomSelectDiv.classList.add('w-5/6');

            const newRoomLabel = document.createElement('label');
            newRoomLabel.setAttribute('for', `additional_salles[${roomCount}]`);
            newRoomLabel.classList.add('block', 'text-gray-700', 'dark:text-gray-300');
            newRoomLabel.textContent = '@lang('Salle Supplémentaire')';

            const newRoomSelect = document.createElement('select');
            newRoomSelect.setAttribute('name', `additional_salles[${roomCount}]`);
            newRoomSelect.setAttribute('id', `additional_salles[${roomCount}]`);
            newRoomSelect.classList.add('form-select', 'mt-1', 'block', 'w-full', 'py-2', 'px-3',
                'border', 'border-gray-300', 'dark:border-gray-600', 'dark:bg-gray-700',
                'dark:text-white', 'rounded-md', 'shadow-sm', 'focus:outline-none',
                'focus:ring-indigo-500', 'focus:border-indigo-500', 'sm:text-sm',
                'additional-salle-select');

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = '@lang('Choisir une salle')';
            newRoomSelect.appendChild(defaultOption);

            salleSelect.querySelectorAll('option').forEach(option => {
                if (option.value !== salleSelect.value && !isRoomAlreadySelected(option
                    .value)) {
                    const newOption = option.cloneNode(true);
                    newRoomSelect.appendChild(newOption);
                }
            });

            const newRoomError = document.createElement('p');
            newRoomError.classList.add('text-red-500', 'text-xs', 'mt-1');
            newRoomSelectDiv.appendChild(newRoomError);

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.classList.add('remove-room', 'w-1/6', 'bg-red-500', 'text-white', 'font-bold',
                'py-2', 'px-4', 'rounded', 'focus:outline-none', 'focus:shadow-outline', 'mt-7',
                'ml-4');
            removeButton.textContent = '@lang('Supprimer')';

            newRoomSelectDiv.appendChild(newRoomLabel);
            newRoomSelectDiv.appendChild(newRoomSelect);

            newRoomDiv.appendChild(newRoomSelectDiv);
            newRoomDiv.appendChild(removeButton);

            additionalRoomsDiv.appendChild(newRoomDiv);

            removeButton.addEventListener('click', function() {
                newRoomDiv.remove();
                updateTotalCapacity();
            });

            newRoomSelect.addEventListener('change', updateTotalCapacity);
        });

        // Add remove event listeners to existing rooms on page load
        const existingRooms = additionalRoomsDiv.querySelectorAll('.additional-room');
        existingRooms.forEach(roomDiv => {
            const removeButton = roomDiv.querySelector('.remove-room');
            removeButton.addEventListener('click', function() {
                roomDiv.remove();
                updateTotalCapacity();
            });
        });
    });
</script>
