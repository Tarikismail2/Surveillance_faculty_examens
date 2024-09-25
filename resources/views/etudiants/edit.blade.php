<?php
use Carbon\Carbon;
?>
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-teal-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-teal-800 leading-tight">
                {{ __('Modifier un Étudiant') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6 border border-gray-200">
                
                <!-- Affichage des erreurs globales -->
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Formulaire d'édition d'étudiant -->
                <form action="{{ route('etudiants.update', $etudiant->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Modifier un Étudiant') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Champs du formulaire étudiant -->
                        @foreach (['code_etudiant' => 'Code Étudiant', 'nom' => 'Nom', 'prenom' => 'Prénom', 'cin' => 'CIN', 'cne' => 'CNE'] as $field => $label)
                            <div class="mb-4">
                                <label for="{{ $field }}" class="block text-gray-700 font-semibold">{{ $label }}</label>
                                <input type="text" id="{{ $field }}" name="{{ $field }}"
                                    value="{{ old($field, $etudiant->$field) }}"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    {{ in_array($field, ['code_etudiant', 'nom', 'prenom', 'cne']) ? 'required' : '' }}>
                                @error($field)
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach

                        <div class="mb-4">
                            <label for="date_naissance" class="block text-gray-700 font-semibold">Date de Naissance</label>
                            <input type="date" id="date_naissance" name="date_naissance"
                                value="{{ old('date_naissance', Carbon::parse($etudiant->date_naissance)->format('Y-m-d')) }}"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('date_naissance')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sélection de la session -->
                        <div class="mb-4">
                            <label for="session_id" class="block text-gray-700 font-semibold">Session</label>
                            <select id="session_id" name="session_id"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($sessions as $session)
                                    <option value="{{ $session->id }}" {{ $etudiant->session_id == $session->id ? 'selected' : '' }}>
                                        {{ $session->type }}  ( {{ $session->date_debut }} - {{ $session->date_fin }} )
                                    </option>
                                @endforeach
                            </select>
                            @error('session_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sélection des modules avec barre de recherche et scroll -->
                        <div class="mb-4 col-span-2">
                            <label for="moduleSearch" class="block text-gray-700 font-semibold">Recherche de modules</label>
                            <input type="text" id="moduleSearch" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Recherchez un module...">

                            <label for="modules" class="block text-gray-700 font-semibold mt-4">Modules</label>
                            <!-- Conteneur avec scroll -->
                            <div id="modulesList" class="space-y-2 overflow-y-auto h-48 border border-gray-300 rounded-md p-2">
                                @foreach ($modules as $module)
                                    <div class="flex items-center module-item">
                                        <input type="checkbox" id="module-{{ $module->id }}" name="modules[]"
                                            value="{{ $module->id }}"
                                            {{ $etudiant->modules->contains($module->id) ? 'checked' : '' }}
                                            class="mr-2 form-checkbox text-indigo-600 focus:ring-indigo-500 focus:border-indigo-500">
                                        <label for="module-{{ $module->id }}" class="text-gray-700">{{ $module->lib_elp }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('modules')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded mt-4">
                        {{ __('Mettre à jour') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript pour la barre de recherche des modules -->
    <script>
        document.getElementById('moduleSearch').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const modules = document.querySelectorAll('.module-item');

            modules.forEach(module => {
                const moduleName = module.querySelector('label').textContent.toLowerCase();
                if (moduleName.includes(searchValue)) {
                    module.style.display = 'block';
                } else {
                    module.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>
