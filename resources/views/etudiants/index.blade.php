<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-white border-b border-gray-200 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Étudiants') }}
            </h2>
            <a href="{{ route('etudiants.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i>
                {{ __('Ajouter étudiant') }}
            </a>
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
                    <!-- Sélecteur de session -->
                    <form method="GET" action="{{ route('etudiants.index') }}" class="mb-4">
                        <label for="session"
                            class="block text-sm font-medium text-gray-700">{{ __('Sélectionner une session') }}</label>
                        <select id="session" name="session_id"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">-- Sélectionnez une session --</option>
                            @foreach ($sessions as $session)
                                <option value="{{ $session->id }}"
                                    {{ $selectedSessionId == $session->id ? 'selected' : '' }}>
                                    {{ $session->type }}  ( {{ $session->date_debut }} -  {{ $session->date_fin }})
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <!-- Tableau des étudiants -->
                    <table id="etudiantsTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Nom Complet') }}
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Les lignes seront ajoutées par DataTables -->
                        </tbody>
                    </table>

                    <!-- Bouton de téléchargement -->
                    @if ($selectedSessionId)
                        <a href="{{ route('test.pdf', ['sessionId' => $selectedSessionId]) }}" id="downloadButton"
                            class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 mt-4 inline-block">
                            Télécharger PDF
                        </a>
                    @else
                        <p>Veuillez sélectionner une session pour télécharger le PDF.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script>
        $(document).ready(function() {
            var table = $('#etudiantsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('etudiants.index') }}',
                    data: function(d) {
                        d.session_id = $('#session').val();
                    },
                    dataSrc: function(json) {
                        if (json.data.length === 0) {
                            $('#etudiantsTable').find('tbody').html(
                                '<tr><td colspan="2" class="text-center">Aucun étudiant disponible pour cette session.</td></tr>'
                            );
                        }
                        return json.data;
                    }
                },
                columns: [
                    {
                        data: 'fullName',
                        name: 'fullName',
                        render: function(data, type, row) {
                            return '<a href="/etudiants/' + row.id +
                                '" class="text-blue-600 hover:text-blue-800 font-medium">' + data +
                                '</a>';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <div class="flex space-x-2">
                                    <a href="/etudiants/${row.id}/edit" class="text-blue-600 hover:text-blue-800 font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L5 12.172V15h2.828l9.586-9.586a2 2 0 000-2.828zM4 13H3v4a1 1 0 001 1h4v-1H4v-3z" />
                                        </svg>
                                    </a>
                                    <form action="/etudiants/${row.id}" method="POST" onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cet étudiant ?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 8a1 1 0 011-1h6a1 1 0 011 1v9a1 1 0 11-2 0v-1H8v1a1 1 0 11-2 0V8zm3-3a1 1 0 00-1-1V3a1 1 0 112 0v1a1 1 0 00-1 1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>`;
                        }
                    }
                ],
                responsive: true,
                paging: true,
                searching: true,
                ordering: true
            });
    
            $('#session').change(function() {
                var selectedSession = $(this).val();
                console.log("Session sélectionnée : " + selectedSession);
                table.ajax.reload(); // Recharger le tableau lorsque la session change
                $('#downloadButton').toggle(selectedSession !== '');
                console.log("Le bouton de téléchargement est : " + ($('#downloadButton').is(':visible') ? 'visible' : 'caché'));
            });
        });
    </script>
    
</x-app-layout>
