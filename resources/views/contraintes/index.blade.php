<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-2xl text-black-600 leading-tight">
                {{ __('Liste des contraintes des enseignants') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">{{ session('success') }}</strong>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">{{ session('error') }}</strong>
                    </div>
                @endif

                <table class="table-auto w-full">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2">Enseignant</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Heure de début</th>
                            <th class="px-4 py-2">Heure de fin</th>
                            <th class="px-4 py-2">Statut</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contraintes as $contrainte)
                            <tr class="bg-white border-b">
                                <td class="px-4 py-2">
                                    @if ($contrainte->enseignant)
                                        {{ $contrainte->enseignant->name }}
                                    @else
                                        {{ __('Aucun enseignant associé') }}
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $contrainte->date }}</td>
                                <td class="px-4 py-2">{{ $contrainte->heure_debut }}</td>
                                <td class="px-4 py-2">{{ $contrainte->heure_fin }}</td>
                                <td class="px-4 py-2">{{ $contrainte->validee ? 'Validée' : 'Non validée' }}</td>
                                <td class="px-4 py-2">
                                    @if (!$contrainte->validee)
                                        <form action="{{ route('contraintes.valider', $contrainte->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <x-button class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                Valider
                                            </x-button>
                                        </form>
                                    @endif
                                    <form action="{{ route('contraintes.annuler', $contrainte->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <x-button class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            Annuler
                                        </x-button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
