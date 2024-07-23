<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-white border-b border-gray-200 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Étudiants') }}
            </h2>
            <a href="{{ route('etudiants.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
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
                    <table id="etudiantsTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Nom Complet') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- DataTables will populate rows here -->
                        </tbody>
                        <a href="{{ route('test.pdf') }}" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                            Télécharger PDF
                        </a>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">

    <script>
        $(document).ready(function() {
            $('#etudiantsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('etudiants.index') }}',
                columns: [
                    { 
                        data: 'fullName', 
                        name: 'fullName',
                        render: function(data, type, row) {
                            return '<a href="/etudiants/' + row.id + '" class="text-blue-600 hover:text-blue-800 font-medium">' + data + '</a>';
                        }
                    },
                    { 
                        data: 'action', 
                        name: 'action', 
                        orderable: false, 
                        searchable: false
                    }
                ],
                responsive: true,
                paging: true,
                searching: true,
                info: true
            });
        });
    </script>
    
</x-app-layout>
