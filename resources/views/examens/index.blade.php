<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Liste des Examens') }}
            </h2>
            <a href="{{ route('examens.create', ['id' => $session->id]) }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 00-2 0v3H6a1 1 0 000 2h3v3a1 1 0 002 0v-3h3a1 1 0 000-2h-3V7z" clip-rule="evenodd" />
                </svg>
                <span class="hidden md:inline">Créer un examen</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Affichage des messages -->
                @if (session('success'))
                    <div class="bg-green-500 text-white p-4 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-500 text-white p-4 rounded mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4 flex justify-between items-center">
                    <a href="{{ route('sessions.index') }}"
                       class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 hover:text-gray-900 rounded-md py-2 px-4 transition-colors duration-300 ease-in-out flex items-center space-x-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour aux sessions</span>
                    </a>
                    <form action="{{ route('examens.assignInvigilatorsToAll') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_session" value="{{ $session->id }}">
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out flex items-center space-x-2">
                            <i class="fas fa-user-check"></i>
                            <span class="hidden md:inline">Affecter les Surveillants Automatiquement</span>
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table id="exams-table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Date</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Heure de Début</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Heure de Fin</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Module</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Filière</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Salles</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Enseignant</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($examens as $examen)
                                <tr>
                                    <td class="px-4 py-2">{{ $examen->date ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $examen->heure_debut ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $examen->heure_fin ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ optional($examen->module)->lib_elp ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ optional($examen->module)->version_etape ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">
                                        @if ($examen->sallesSupplementaires && $examen->sallesSupplementaires->isNotEmpty())
                                            @foreach ($examen->sallesSupplementaires as $salle)
                                                    {{ $salle->name }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ optional($examen->enseignant)->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('examens.editExamen', ['id' => $examen->id]) }}"
                                               class="text-indigo-600 hover:text-indigo-900 flex items-center space-x-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L5 12.172V15h2.828l9.586-9.586a2 2 0 000-2.828zM4 13H3v4a1 1 0 001 1h4v-1H4v-3z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('examens.destroy', $examen->id) }}" method="POST"
                                                  class="inline" onsubmit="return confirmDelete();">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 flex items-center space-x-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M6 8a1 1 0 011-1h6a1 1 0 011 1v9a1 1 0 11-2 0v-1H8v1a1 1 0 11-2 0V8zm3-3a1 1 0 00-1-1V3a1 1 0 112 0v1a1 1 0 00-1 1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-gray-500">Aucun examen trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDelete() {
                return confirm("Êtes-vous sûr de vouloir supprimer cet examen ?");
            }

            $(document).ready(function() {
                $('#exams-table').DataTable({
                    "order": [[0, 'desc']], // Trier par la date en ordre décroissant
                    "language": {
                        "sSearch": "Recherche:",
                        "sLengthMenu": "Afficher _MENU_ lignes",
                        "sInfo": "Affichage de _START_ à _END_ sur _TOTAL_ lignes",
                        "sInfoEmpty": "Aucune ligne à afficher",
                        "sInfoFiltered": "(filtré à partir de _MAX_ lignes au total)",
                        "sZeroRecords": "Aucun résultat trouvé",
                        "oPaginate": {
                            "sNext": "Suivant",
                            "sPrevious": "Précédent"
                        },
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
