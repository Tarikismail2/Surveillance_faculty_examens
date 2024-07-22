<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-green-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-green-800 leading-tight">
                {{ __('Gestion des Étudiants') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6 border border-gray-200">
                <!-- Formulaire de création d'étudiant -->
                <form action="{{ route('etudiants.store') }}" method="POST">
                    @csrf
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Créer un Étudiant') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Champs du formulaire étudiant -->
                        @foreach (['code_etudiant' => 'Code Étudiant', 'nom' => 'Nom', 'prenom' => 'Prénom', 'cin' => 'CIN', 'cne' => 'CNE'] as $field => $label)
                            <div class="mb-4">
                                <label for="{{ $field }}" class="block text-gray-700 font-semibold">{{ $label }}</label>
                                <input type="{{ $field == 'date_naissance' ? 'date' : 'text' }}" id="{{ $field }}" name="{{ $field }}"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    {{ $field == 'code_etudiant' || $field == 'nom' || $field == 'prenom' || $field == 'cne' ? 'required' : '' }}>
                                @error($field)
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                        <div class="mb-4">
                            <label for="date_naissance" class="block text-gray-700 font-semibold">Date de Naissance</label>
                            <input type="date" id="date_naissance" name="date_naissance"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('date_naissance')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sélection des modules -->
                        <div class="mb-4 col-span-2">
                            <label for="modules" class="block text-gray-700 font-semibold">Modules</label>
                            <div class="space-y-2">
                                @foreach ($modules->chunk(10) as $chunk) <!-- Pagination simple -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        @foreach ($chunk as $module)
                                            <div class="flex items-center">
                                                <input type="checkbox" id="module-{{ $module->id }}" name="modules[]"
                                                    value="{{ $module->id }}"
                                                    class="mr-2 form-checkbox text-indigo-600 focus:ring-indigo-500 focus:border-indigo-500">
                                                <label for="module-{{ $module->id }}" class="text-gray-700">{{ $module->lib_elp }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            @error('modules')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-4">
                        {{ __('Créer') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
