<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-white border-b border-gray-200 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Étudiants') }}
            </h2>

        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                @if (session('success'))
                    <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-4">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span class="block">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="p-6">
                    <!-- Formulaire pour sélectionner la session -->
                    <form id="sessionForm" method="GET" action="{{ route('etudiants.index') }}" class="mb-4">
                        <label for="session" class="block text-sm font-medium text-gray-700">{{ __('Sélectionner une session') }}</label>
                        <select id="session" name="session_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-lg shadow-sm">
                            <option value="">-- Sélectionnez une session --</option>
                            @foreach ($sessions as $session)
                                <option value="{{ $session->id }}" {{ $selectedSessionId == $session->id ? 'selected' : '' }}>
                                    {{ $session->type }} ({{ $session->date_debut }} - {{ $session->date_fin }})
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <!-- Tableau des étudiants -->
                    @if (!empty($etudiants))
                        <table id="etudiantsTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Nom Complet') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($etudiants as $etudiant)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <a href="{{ route('etudiants.show', $etudiant->id) }}" class="text-blue-600 hover:text-blue-900">
                                                {{ $etudiant->nom }} {{ $etudiant->prenom }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <a href="{{ route('etudiants.edit', $etudiant->id) }}" class="text-blue-600 dark:text-blue-400 hover:text-yellow-600 dark:hover:text-yellow-400 mr-2" title="{{ __('Modifier') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L5 12.172V15h2.828l9.586-9.586a2 2 0 000-2.828zM4 13H3v4a1 1 0 001 1h4v-1H4v-3z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('etudiants.destroy', $etudiant->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette etudiant ?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-600" title="{{ __('Supprimer') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011-1h6a1 1 0 011 1v9a1 1 0 11-2 0v-1H8v1a1 1 0 11-2 0V8zm3-3a1 1 0 00-1-1V3a1 1 0 112 0v1a1 1 0 00-1 1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-sm text-gray-500 mt-4">Aucun étudiant n'est disponible pour cette session.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Inclure DataTables CSS et JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

    <!-- Script pour initialiser DataTables -->
    <script>
        document.getElementById('session').addEventListener('change', function () {
            document.getElementById('sessionForm').submit();
        });

        $(document).ready(function() {
            $('#etudiantsTable').DataTable({
                "language": {
                    "lengthMenu": "Afficher _MENU_ étudiants par page",
                    "zeroRecords": "Aucun étudiant trouvé",
                    "info": "Affichage de _PAGE_ sur _PAGES_",
                    "infoEmpty": "Aucun étudiant disponible",
                    "infoFiltered": "(filtré de _MAX_ étudiants au total)",
                    "search": "Rechercher :",
                    "paginate": {
                        "first": "Premier",
                        "last": "Dernier",
                        "next": "Suivant",
                        "previous": "Précédent"
                    }
                },
                "paging": true,
                "searching": true,
                "ordering": true
            });
        });
    </script>
        {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
        <script>
            $(document).ready(function() {
                $('#session').select2({
                    placeholder: "Choisir une session",
                    allowClear: true
                }).on('change', updateDownloadLink);
            });
            </script> --}}
</x-app-layout>
