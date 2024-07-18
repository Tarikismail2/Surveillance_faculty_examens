<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('Modifier l\'examen')
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
                        <label for="heure_debut" class="block text-gray-700 dark:text-gray-300">@lang('Heure de Début')</label>
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
                        <select class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="filiere" name="id_filiere" required>
                            <option value="">@lang('Sélectionnez une filière')</option>
                            @foreach ($filieres as $filiere)
                                <option value="{{ $filiere->version_etape }}" {{ old('id_filiere', optional($examen->module)->version_etape) == $filiere->version_etape ? 'selected' : '' }}>
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
                        <select class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="module" name="id_module" required>
                            <option value="">@lang('Sélectionnez un module')</option>
                            <!-- Les modules seront remplis dynamiquement -->
                        </select>
                        @error('id_module')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="id_enseignant" class="block text-gray-700 dark:text-gray-300">@lang('Enseignant')</label>
                        <select name="id_enseignant" id="id_enseignant"
                            class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">@lang('Choisir un enseignant')</option>
                            @foreach ($enseignants as $enseignant)
                                <option value="{{ $enseignant->id }}" {{ old('id_enseignant', $examen->id_enseignant) == $enseignant->id ? 'selected' : '' }}>{{ $enseignant->name }}</option>
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
                                class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <option value="">@lang('Choisir une salle')</option>
                            @foreach ($salles as $salle)
                                <option value="{{ $salle->id }}" {{ old('id_salle', $examen->id_salle) == $salle->id ? 'selected' : '' }}>{{ $salle->name }} ({{ $salle->capacite }})</option>
                            @endforeach
                        </select>
                        @error('id_salle')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Salles supplémentaires -->
                    <div id="additional-rooms" class="form-group mb-4">
                        @if ($examen->additionalSalles)
                            @foreach ($examen->additionalSalles as $additionalSalle)
                                @if ($additionalSalle->id != $primaryRoomId)
                                    <div class="flex mb-4 additional-room">
                                        <div class="w-5/6">
                                            <label for="additional_salles[{{ $loop->index }}]"
                                                   class="block text-gray-700 dark:text-gray-300">@lang('Salle Supplémentaire')</label>
                                            <select name="additional_salles[{{ $loop->index }}]" id="additional_salles[{{ $loop->index }}]"
                                                    class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm additional-salle-select">
                                                <option value="">@lang('Choisir une salle')</option>
                                                @foreach ($salles as $salle)
                                                    @if ($salle->id != $primaryRoomId)
                                                        <option value="{{ $salle->id }}" {{ old('additional_salles.' . $loop->index, $additionalSalle->id) == $salle->id ? 'selected' : '' }}>
                                                            {{ $salle->name }} ({{ $salle->capacite }})
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('additional_salles.' . $loop->index)
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <button type="button"
                                                class="remove-room w-1/6 bg-red-500 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-7 ml-4">
                                            @lang('Supprimer')
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    
                    <div class="form-group mb-4">
                        <button type="button" id="add-room-button"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                            @lang('Ajouter une autre salle')
                        </button>
                    </div>                    
                    

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('examens.index', ['id' => $selected_session->id]) }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> @lang('Retour')
                        </a>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                            <i class="fas fa-save mr-2"></i> @lang('Modifier l\'examen')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
   document.addEventListener('DOMContentLoaded', function () {
        function updateModuleOptions() {
            var filiereId = document.getElementById('filiere').value;
            var moduleSelect = document.getElementById('module');
            moduleSelect.innerHTML = '<option value="">@lang('Sélectionnez un module')</option>';

            if (filiereId) {
                fetch('/examens/getModulesByFiliere/' + filiereId)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Modules:', data); // Debugging: log fetched modules
                        data.forEach(module => {
                            var option = document.createElement('option');
                            option.value = module.id;
                            option.textContent = `${module.lib_elp} (${module.inscriptions_count} @lang('inscrits'))`;

                            // Debugging: log each module ID and the selected module ID
                            console.log('Comparing:', "{{ $examen->id_module }}", 'and', module.id);

                            // Compare as strings to avoid type mismatches
                            if ("{{ $examen->id_module }}" == module.id.toString()) {
                                option.selected = true;
                                console.log('Selected module:', module.id); 
                            }
                            moduleSelect.appendChild(option);
                        });

                        // Debugging: log final state of moduleSelect
                        console.log('Final module select state:', moduleSelect);
                    })
                    .catch(error => console.error('Error fetching modules:', error));
            }
        }

        document.getElementById('filiere').addEventListener('change', updateModuleOptions);

        // Update module options on page load
        updateModuleOptions();
 
     document.getElementById('add-room-button').addEventListener('click', function () {
         var additionalRoomsDiv = document.getElementById('additional-rooms');
         var roomCount = additionalRoomsDiv.children.length;
 
         var newRoomDiv = document.createElement('div');
         newRoomDiv.classList.add('flex', 'mb-4', 'additional-room');
 
         var newRoomSelectDiv = document.createElement('div');
         newRoomSelectDiv.classList.add('w-5/6');
 
         var newRoomLabel = document.createElement('label');
         newRoomLabel.setAttribute('for', 'additional_salles[' + roomCount + ']');
         newRoomLabel.classList.add('block', 'text-gray-700', 'dark:text-gray-300');
         newRoomLabel.textContent = '@lang('Salle Supplémentaire')';
 
         var newRoomSelect = document.createElement('select');
         newRoomSelect.setAttribute('name', 'additional_salles[' + roomCount + ']');
         newRoomSelect.setAttribute('id', 'additional_salles[' + roomCount + ']');
         newRoomSelect.classList.add('form-select', 'mt-1', 'block', 'w-full', 'py-2', 'px-3', 'border', 'border-gray-300', 'dark:border-gray-600', 'dark:bg-gray-700', 'dark:text-white', 'rounded-md', 'shadow-sm', 'focus:outline-none', 'focus:ring-indigo-500', 'focus:border-indigo-500', 'sm:text-sm');
 
         newRoomSelect.innerHTML = '<option value="">@lang('Choisir une salle')</option>';
         @foreach ($salles as $salle)
             newRoomSelect.innerHTML += '<option value="{{ $salle->id }}">{{ $salle->name }} ({{ $salle->capacite }})</option>';
         @endforeach
 
         var newRemoveButton = document.createElement('button');
         newRemoveButton.setAttribute('type', 'button');
         newRemoveButton.classList.add('remove-room', 'w-1/6', 'bg-red-500', 'text-white', 'font-bold', 'py-2', 'px-4', 'rounded', 'focus:outline-none', 'focus:shadow-outline', 'mt-7', 'ml-4');
         newRemoveButton.textContent = '@lang('Supprimer')';
 
         newRoomSelectDiv.appendChild(newRoomLabel);
         newRoomSelectDiv.appendChild(newRoomSelect);
         newRoomDiv.appendChild(newRoomSelectDiv);
         newRoomDiv.appendChild(newRemoveButton);
 
         additionalRoomsDiv.appendChild(newRoomDiv);
     });
 
     document.getElementById('additional-rooms').addEventListener('click', function (event) {
         if (event.target.classList.contains('remove-room')) {
             event.target.closest('.additional-room').remove();
         }
     });
 });
 </script>
 
 