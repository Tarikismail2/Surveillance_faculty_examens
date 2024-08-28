<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-2xl text-black-600 leading-tight">
                {{ __('Modules de la Filière : ' . $filiere->code_etape) }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="overflow-x-auto">
                    <table id="moduleTable" class="min-w-full bg-white dark:bg-gray-800 shadow-md rounded-lg">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Code Module
                                </th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Nom Module
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
            $('#moduleTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('filiere.show', $filiere->id) }}',
                columns: [
                   { data: 'code_elp', name: 'code_elp' },
                    { data: 'lib_elp', name: 'lib_elp' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                "order": [[0, 'asc']] 
            });
        });
    </script>
</x-app-layout>
