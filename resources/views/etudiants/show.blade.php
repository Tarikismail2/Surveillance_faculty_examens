<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-teal-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-teal-800 leading-tight">
                {{ __('Détails de l\'Étudiant') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Informations de l\'Étudiant') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Informations de l'étudiant -->
                    @foreach (['code_etudiant' => 'Code Étudiant', 'nom' => 'Nom', 'prenom' => 'Prénom', 'cin' => 'CIN', 'cne' => 'CNE', 'date_naissance' => 'Date de Naissance'] as $field => $label)
                        <div class="mb-4">
                            <p class="font-semibold text-gray-700">{{ $label }}:</p>
                            <p class="text-gray-800">{{ $etudiant->$field }}</p>
                        </div>
                    @endforeach

                    <!-- Information de la session -->
                    <div class="mb-4">
                        <p class="font-semibold text-gray-700">{{ __('Session') }}:</p>
                        <p class="text-gray-800">{{ $session->type }}  ( {{ $session->date_debut }} -  {{ $session->date_fin }})</p>
                    </div>
                </div>

                <h4 class="text-lg font-semibold text-gray-800 mt-6 mb-4">{{ __('Modules Inscrits') }}</h4>
                <ul class="list-disc list-inside text-gray-800">
                    @foreach ($modules as $module)
                        <li>{{ $module->lib_elp }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
