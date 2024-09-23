<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-2xl text-black-600 leading-tight">
                {{ __('Liste des Filières') }}
            </h2>
            <a href="{{ route('filiere.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 00-2 0v3H6a1 1 0 000 2h3v3a1 1 0 002 0v-3h3a1 1 0 000-2h-3V7z" clip-rule="evenodd" />
                </svg>
                <span class="hidden md:inline">Créer une Nouvelle Filière</span>
            </a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table id="filiereTable" class="min-w-full bg-white dark:bg-gray-800 shadow-md rounded-lg">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Code Étape
                                </th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Version Étape
                                </th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTables will populate this -->
                        </tbody>
                    </table>
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
    const id_session = '{{ request()->id_session }}';  // Fetch the id_session from the request
    
    $('#filiereTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('filiere.index', ['id_session' => ':id_session']) }}'.replace(':id_session', id_session),
            data: function (d) {
                d.id_session = id_session;  // Pass the id_session to the server
            }
        },
        columns: [
            { data: 'code_etape', name: 'code_etape' },
            { data: 'version_etape', name: 'version_etape' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        "order": [[0, 'asc']]
    });
});

    </script>
</x-app-layout>