<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Liste des Examens') }}
            </h2>
            <a href="{{ route('examens.create', ['id' => $session->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out flex items-center space-x-2"
                onclick="return confirm('{{ __('Êtes-vous sûr de vouloir ajouter un nouvel examen ?') }}');">
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
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                <div class="mb-4 flex justify-end">
                    <a href="{{ route('sessions.index') }}" class="inline-block bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 hover:text-gray-900 dark:hover:text-gray-100 rounded-md py-2 px-4 transition-colors duration-300 ease-in-out flex items-center space-x-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour aux sessions</span>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table id="exams-table" class="display min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th>Date</th>
                                <th>Heure de Début</th>
                                <th>Heure de Fin</th>
                                <th>Module</th>
                                <th>Filière</th>
                                <th>Salles</th>
                                <th>Enseignant</th>
                                <th>Actions</th>
                                <th>Affectation des surveillants</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($examens as $examen)
                                <tr>
                                    <td>{{ $examen->date ?? 'N/A' }}</td>
                                    <td>{{ $examen->heure_debut ?? 'N/A' }}</td>
                                    <td>{{ $examen->heure_fin ?? 'N/A' }}</td>
                                    <td>{{ optional($examen->module)->lib_elp ?? 'N/A' }}</td>
                                    <td>{{ optional($examen->module)->version_etape ?? 'N/A' }}</td>
                                    <td>
                                        @foreach ($examen->salles as $salle)
                                            {{ $salle->name }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </td>
                                    <td>{{ optional($examen->enseignant)->name ?? 'N/A' }}</td>
                                    <td class="flex space-x-2">
                                        <a href="{{ route('examens.editExamen', ['id' => $examen->id]) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center space-x-1" onclick="return confirm('{{ __('Êtes-vous sûr de vouloir modifier cet examen ?') }}');">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L5 12.172V15h2.828l9.586-9.586a2 2 0 000-2.828zM4 13H3v4a1 1 0 001 1h4v-1H4v-3z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('examens.destroy', $examen->id) }}" method="POST" class="inline" onsubmit="return confirmDelete();">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 flex items-center space-x-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011-1h6a1 1 0 011 1v9a1 1 0 11-2 0v-1H8v1a1 1 0 11-2 0V8zm3-3a1 1 0 00-1-1V3a1 1 0 112 0v1a1 1 0 00-1 1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        @if ($examen->hasAssignedInvigilators())
                                            <a href="{{ route('examens.showForm', $examen->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center space-x-2">
                                                <i class="fas fa-eye"></i>
                                                <span class="hidden md:inline">Voir Affectation</span>
                                            </a>
                                        @else
                                            <a href="{{ route('examens.showAssignInvigilatorsForm', $examen->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center space-x-2">
                                                <i class="fas fa-user-plus"></i>
                                                <span class="hidden md:inline">Affectation</span>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-sm font-medium">Aucun examen trouvé pour cette sélection.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Initialisation de DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#exams-table').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr.json" // Langue française
                },
                "responsive": true,
                "paging": true,
                "searching": true,
                "info": true,
                "lengthChange": true
            });
        });

        function confirmDelete() {
            return confirm('Êtes-vous sûr de vouloir supprimer cet examen ?');
        }
    </script>
</x-app-layout>
