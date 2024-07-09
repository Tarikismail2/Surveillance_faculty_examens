<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('Mettre à jour l\'examen')
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

                <form action="{{ route('examens.update', $examen->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-4">
                        <label for="date" class="block text-gray-700 dark:text-gray-300">@lang('Date')</label>
                        <input type="date" name="date" id="date" value="{{ old('date', $examen->date) }}"
                            class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                        @error('date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="heure_debut" class="block text-gray-700 dark:text-gray-300">@lang('Heure de Début')</label>
                        <input type="time" name="heure_debut" id="heure_debut" value="{{ old('heure_debut', $examen->heure_debut) }}"
                            class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                        @error('heure_debut')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="heure_fin" class="block text-gray-700 dark:text-gray-300">@lang('Heure de Fin')</label>
                        <input type="time" name="heure_fin" id="heure_fin" value="{{ old('heure_fin', $examen->heure_fin) }}"
                            class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                        @error('heure_fin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="filiere">Filière</label>
                        <select class="form-control" id="filiere" name="id_filiere" required>
                            <option value="">Sélectionnez une filière</option>
                            @foreach ($filieres as $filiere)
                                <option value="{{ $filiere->code_etape }}" {{ $filiere->code_etape == old('id_filiere', $examen->module->code_etape) ? 'selected' : '' }}>{{ $filiere->code_etape }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="module">Module</label>
                        <select class="form-control" id="module" name="id_module" required>
                            <option value="">Sélectionnez un module</option>
                            @foreach ($modules as $module)
                                <option value="{{ $module->id }}" {{ $module->id == old('id_module', $examen->id_module) ? 'selected' : '' }}>{{ $module->lib_elp }} ({{ $module->inscriptions_count }} @lang('inscrits'))</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="id_enseignant" class="block text-gray-700 dark:text-gray-300">@lang('Enseignant')</label>
                        <select name="id_enseignant" id="id_enseignant"
                            class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">@lang('Choisir un enseignant')</option>
                            @foreach ($enseignants as $enseignant)
                                <option value="{{ $enseignant->id }}" {{ $enseignant->id == old('id_enseignant', $examen->id_enseignant) ? 'selected' : '' }}>{{ $enseignant->name }}</option>
                            @endforeach
                        </select>
                        @error('id_enseignant')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="id_session" class="block text-gray-700 dark:text-gray-300">@lang('Session')</label>
                        <select name="id_session" id="id_session"
                            class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach ($sessions as $session)
                                <option value="{{ $session->id }}" {{ $session->id == old('id_session', $examen->id_session) ? 'selected' : '' }}>{{ $session->type }} ({{ $session->date_debut }} - {{ $session->date_fin }})</option>
                            @endforeach
                        </select>
                        @error('id_session')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="id_salle" class="block text-gray-700 dark:text-gray-300">@lang('Salle')</label>
                        <select name="id_salle" id="id_salle"
                            class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">@lang('Choisir une salle')</option>
                            @foreach ($salles as $salle)
                                <option value="{{ $salle->id }}" {{ $salle->id == old('id_salle', $examen->id_salle) ? 'selected' : '' }}>{{ $salle->name }} ({{ $salle->capacite }})</option>
                            @endforeach
                        </select>
                        @error('id_salle')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <button type="button" id="add-room-button"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                            @lang('Ajouter une autre salle')
                        </button>
                    </div>

                    <div id="additional-rooms">
                        @foreach (old('additional_salles', $examen->salles->pluck('id')->toArray() ?? []) as $additional_salle)
                            <div class="form-group mb-4">
                                <label for="additional_salles[]" class="block text-gray-700 dark:text-gray-300">@lang('Salle')</label>
                                <select name="additional_salles[]" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">@lang('Choisir une salle')</option>
                                    @foreach ($salles as $salle)
                                        <option value="{{ $salle->id }}" {{ $salle->id == $additional_salle ? 'selected' : '' }}>{{ $salle->name }} ({{ $salle->capacite }})</option>
                                    @endforeach
                                </select>
                                @error('additional_salles[]')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('examens.index') }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> @lang('Retour')
                        </a>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                            <i class="fas fa-save mr-2"></i> @lang('Mettre à jour')
                        </button>
                    </div>
                </form>

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
                                        option.textContent = `${module.lib_elp} (${module.inscriptions_count} @lang('inscrits'))`;
                                        moduleSelect.appendChild(option);
                                    });
                                })
                                .catch(error => console.error('Error fetching modules:', error));
                        }
                    });

                    document.getElementById('add-room-button').addEventListener('click', function() {
                        const additionalRooms = document.getElementById('additional-rooms');
                        const roomSelect = document.createElement('div');
                        roomSelect.className = 'form-group mb-4';
                        roomSelect.innerHTML = `
                            <label for="additional_salles[]" class="block text-gray-700 dark:text-gray-300">@lang('Salle')</label>
                            <select name="additional_salles[]" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @foreach ($salles as $salle)
                                    <option value="{{ $salle->id }}">{{ $salle->name }} ({{ $salle->capacite }})</option>
                                @endforeach
                            </select>
                        `;
                        additionalRooms.appendChild(roomSelect);
                    });
                </script>

            </div>
        </div>
    </div>
</x-app-layout>
