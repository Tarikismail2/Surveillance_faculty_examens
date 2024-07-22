<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Liste des Examens') }}
            </h2>
            <a href="{{ route('examens.create', ['id' => $session->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out flex items-center space-x-2">
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

                {{-- <div class="mb-6">
                    <form method="GET" action="{{ route('examens.index', ['sessionId' => $session->id]) }}" class="flex space-x-4">
                        <div>
                            <label for="filiere_id" class="block text-sm font-medium text-gray-700">Filière</label>
                            <select id="filiere_id" name="filiere_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Toutes les filières</option>
                                @foreach ($filieres as $filiere)
                                    <option value="{{ $filiere->version_etape }}" {{ request('filiere_id') == $filiere->version_etape ? 'selected' : '' }}>
                                        {{ $filiere->version_etape }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center space-x-2">
                                <i class="fas fa-filter"></i>
                                <span>Filtrer</span>
                            </button>
                        </div>
                    </form>
                </div> --}}


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
                                        <a href="{{ route('examens.editExamen', ['id' => $examen->id]) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center space-x-1">
                                            <i class="fas fa-edit"></i>
                                            {{-- <span class="hidden md:inline">Modifier</span> --}}
                                        </a>
                                        <form action="{{ route('examens.destroy', $examen->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 flex items-center space-x-1">
                                                <i class="fas fa-trash-alt"></i>
                                                {{-- <span class="hidden md:inline">Supprimer</span> --}}
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
    </script>
</x-app-layout>
