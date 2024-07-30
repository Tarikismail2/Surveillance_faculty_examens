<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-white border-b border-gray-200 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Étudiants') }}
            </h2>
            <a href="{{ route('etudiants.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center"
                onclick="return confirm('{{ __('Êtes-vous sûr de vouloir ajouter un nouvel enseignant ?') }}');">
                <i class="fas fa-plus mr-2"></i>
                {{ __('Ajouter étudiant') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <!-- Messages de succès -->
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
                            <!-- DataTables will populate rows here -->
                        </tbody>
                    </table>
                    <a href="{{ route('test.pdf') }}"
                        class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 mt-4 inline-block">
                        Télécharger PDF
                    </a>
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
                columns: [{
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
                                    <a href="/etudiants/${row.id}/edit" class="text-blue-600 hover:text-blue-800 font-medium" onclick="return confirm('{{ __('Êtes-vous sûr de vouloir modifier cet enseignant ?') }}');">
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
                info: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
                },
                initComplete: function() {
                    $('#etudiantsTable_paginate .paginate_button').addClass(
                        'py-2 px-4 border rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300');
                    $('#etudiantsTable_paginate .paginate_button.current').addClass(
                        'bg-blue-600 text-white');
                    $('#etudiantsTable_info').addClass('text-gray-700 text-sm');
                    $('#etudiantsTable_filter input').addClass(
                        'border border-gray-300 rounded-lg py-2 px-4');
                    $('#etudiantsTable_length select').addClass(
                        'border border-gray-300 rounded-lg py-2 px-4');
                    $('#etudiantsTable_processing').addClass(
                        'text-gray-700 font-medium bg-gray-100 p-2 rounded-lg');
                    $('#etudiantsTable_paginate').addClass('flex items-center space-x-2 mt-4');
                    $('#etudiantsTable_filter').addClass('flex items-center space-x-4 mt-4');
                }
            });
        });
    </script>
</x-app-layout>
