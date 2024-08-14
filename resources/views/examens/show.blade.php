<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails de l\'Examen') }}
            </h2>
            <a href="{{ route('examens.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 00-2 0v3H6a1 1 0 000 2h3v3a1 1 0 002 0v-3h3a1 1 0 000-2h-3V7z" clip-rule="evenodd" />
                </svg>
                <span class="hidden md:inline">Retour à la liste des examens</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">{{ $examen->module->lib_elp }} ({{ $examen->module->version_etape }})</h3>
                <div class="mb-6">
                    <p><strong>Date:</strong> {{ $examen->date }}</p>
                    <p><strong>Heure de Début:</strong> {{ $examen->heure_debut }}</p>
                    <p><strong>Heure de Fin:</strong> {{ $examen->heure_fin }}</p>
                </div>
                <h4 class="font-semibold text-md text-gray-800 leading-tight mb-4">Salles et Surveillants:</h4>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salle</th>
                            <th class="py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surveillants</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sallesAffectees as $salle)
                            <tr>
                                <td class="py-2 border-b border-gray-200">{{ $salle->name }}</td>
                                <td class="py-2 border-b border-gray-200">
                                    @foreach ($salle->enseignants as $enseignant)
                                        {{ $enseignant->name }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-6">
                    <a href="{{ route('examens.index') }}" class="bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 hover:text-gray-900 dark:hover:text-gray-100 rounded-md py-2 px-4 transition-colors duration-300 ease-in-out flex items-center space-x-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour aux examens</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
