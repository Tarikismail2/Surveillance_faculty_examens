
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
                    <!-- Formulaire pour sélectionner la session -->
                    <form id="sessionForm" method="GET" action="{{ route('etudiants.index') }}" class="mb-4">
                        <label for="session"
                            class="block text-sm font-medium text-gray-700">{{ __('Sélectionner une session') }}</label>
                        <select id="session" name="session_id"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">-- Sélectionnez une session --</option>
                            @foreach ($sessions as $session)
                                <option value="{{ $session->id }}"
                                    {{ $selectedSessionId == $session->id ? 'selected' : '' }}>
                                    {{ $session->type }} ( {{ $session->date_debut }} - {{ $session->date_fin }})
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

                    <!-- Formulaire pour le bouton de téléchargement -->
                    <form id="downloadForm" action="{{ route('test.pdf', ['sessionId' => $session->id]) }}" method="GET" style="display:none;">
                        <input type="hidden" id="selectedSessionId" name="session_id" value="">
                        <button type="submit" id="downloadButton"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Télécharger le PDF
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- DataTables Scripts -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    
    <script>
        $(document).ready(function() {
            var table = $('#etudiantsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('etudiants.index') }}',
                    data: function(d) {
                        d.session_id = $('#session').val();
                    }
                },
                columns: [{
                        data: 'fullName',
                        name: 'fullName'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#session').change(function() {
                table.ajax.reload();
                var selectedSession = $(this).val();
                $('#selectedSessionId').val(selectedSession);

                if (selectedSession) {
                    $('#downloadForm').show(); // Afficher le formulaire de téléchargement
                } else {
                    $('#downloadForm').hide(); // Masquer le formulaire de téléchargement
                }
            });

            if ($('#session').val()) {
                $('#downloadForm').show();
            }
        });
    </script>

</x-app-layout>
