<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">            
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails de l\'Étudiant') }}
            </h2>
            <a href="{{ route('etudiants.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-2">{{ __('Informations Personnelles') }}</h3>
                <p><strong>{{ __('Nom') }}:</strong> {{ $etudiant->nom }}</p>
                <p><strong>{{ __('Prénom') }}:</strong> {{ $etudiant->prenom }}</p>
                <p><strong>{{ __('CIN') }}:</strong> {{ $etudiant->cin ?? __('Non spécifié') }}</p>
                <p><strong>{{ __('CNE') }}:</strong> {{ $etudiant->cne }}</p>
                <p><strong>{{ __('Date de Naissance') }}:</strong> 
                    {{ $etudiant->date_naissance ? $etudiant->date_naissance->format('d/m/Y') : __('Non spécifié') }}
                </p>
                <div class="flex justify-end mt-4">
                    <a href="{{ route('etudiants.edit', ['etudiant' => $etudiant->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                        {{ __('Modifier') }}
                    </a>
                    <form action="{{ route('etudiants.destroy', ['etudiant' => $etudiant->id]) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Supprimer') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
