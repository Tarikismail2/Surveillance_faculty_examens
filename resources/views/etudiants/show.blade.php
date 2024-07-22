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
            <div class="bg-white shadow-lg rounded-lg p-6 border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 10V3a1 1 0 00-1-1h-1a1 1 0 00-1 1v7a1 1 0 001 1h1a1 1 0 001-1zM9 10V3a1 1 0 00-1-1H7a1 1 0 00-1 1v7a1 1 0 001 1h1a1 1 0 001-1zM18 7V3a1 1 0 00-1-1h-1a1 1 0 00-1 1v4a1 1 0 001 1h1a1 1 0 001-1zM21 7V3a1 1 0 00-1-1h-1a1 1 0 00-1 1v4a1 1 0 001 1h1a1 1 0 001-1zM5 15h14a2 2 0 01-2 2H7a2 2 0 01-2-2zM3 12h18a2 2 0 01-2 2H5a2 2 0 01-2-2z" /></svg>
                            <label class="text-gray-700 font-semibold">Nom</label>
                        </div>
                        <p class="text-gray-800 mt-1">{{ $etudiant->nom }}</p>
                    </div>
                    <div class="mb-4">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v6a1 1 0 001 1h3m-4 4v3m4-8h-4a1 1 0 01-1-1V3a1 1 0 011-1h4a1 1 0 011 1v10a1 1 0 01-1 1z" /></svg>
                            <label class="text-gray-700 font-semibold">Prénom</label>
                        </div>
                        <p class="text-gray-800 mt-1">{{ $etudiant->prenom }}</p>
                    </div>
                    <div class="mb-4">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V5a1 1 0 00-1-1h-1a1 1 0 00-1 1v6a1 1 0 001 1h1a1 1 0 001-1zM8 13V7a1 1 0 00-1-1H6a1 1 0 00-1 1v6a1 1 0 001 1h1a1 1 0 001-1zM20 15V9a1 1 0 00-1-1h-1a1 1 0 00-1 1v6a1 1 0 001 1h1a1 1 0 001-1zM4 15V9a1 1 0 00-1-1H2a1 1 0 00-1 1v6a1 1 0 001 1h1a1 1 0 001-1z" /></svg>
                            <label class="text-gray-700 font-semibold">CIN</label>
                        </div>
                        <p class="text-gray-800 mt-1">{{ $etudiant->cin }}</p>
                    </div>
                    <div class="mb-4">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13H5v-2h14v2zM2 13h.01M2 5h20v12H2V5z" /></svg>
                            <label class="text-gray-700 font-semibold">CNE</label>
                        </div>
                        <p class="text-gray-800 mt-1">{{ $etudiant->cne }}</p>
                    </div>
                    <div class="mb-4">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v6a1 1 0 001 1h1m-1-1h1m-4 1V6a1 1 0 00-1-1H8a1 1 0 00-1 1v3a1 1 0 001 1h3a1 1 0 001-1z" /></svg>
                            <label class="text-gray-700 font-semibold">Date de Naissance</label>
                        </div>
                        <p class="text-gray-800 mt-1">{{ $etudiant->date_naissance }}</p>
                    </div>
                    <div class="mb-4 col-span-2">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18" /></svg>
                            <label class="text-gray-700 font-semibold">Modules</label>
                        </div>
                        <ul class="list-disc list-inside text-gray-800 mt-1">
                            @foreach($modules as $module)
                                <li>{{ $module->lib_elp }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('etudiants.edit', $etudiant->id) }}" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Modifier') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
