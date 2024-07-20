<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Enseignants') }}
            </h2>
            <a href="{{ route('enseignants.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md">
                {{ __('Ajouter un Enseignant') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table id="enseignants-table" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        {{ __('Nom') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        {{ __('Email') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        {{ __('Département') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- DataTables will populate the rows here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <!-- Custom DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom-datatables.css') }}">
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('#enseignants-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('enseignants.index') }}',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'department_name', name: 'department_name' },
                    { 
                        data: null, 
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            return `
                                <a href="/enseignants/${data.id}/edit" class="text-yellow-600 hover:text-yellow-700 font-medium">Modifier</a>
                                <form action="/enseignants/${data.id}" method="POST" class="inline-block" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium">Supprimer</button>
                                </form>
                            `;
                        }
                    }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/French.json"
                },
                initComplete: function () {
                    // Style des éléments de DataTables
                    $('#enseignants-table_paginate .paginate_button').addClass('py-2 px-4 border rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300');
                    $('#enseignants-table_paginate .paginate_button.current').addClass('bg-blue-600 text-white');
                    $('#enseignants-table_info').addClass('text-gray-700 text-sm');
                    $('#enseignants-table_filter input').addClass('border border-gray-300 rounded-lg py-2 px-4');
                    $('#enseignants-table_length select').addClass('border border-gray-300 rounded-lg py-2 px-4');
                    $('#enseignants-table_processing').addClass('text-gray-700 font-medium bg-gray-100 p-2 rounded-lg');
                    
                    // Styles spécifiques pour la pagination et la recherche
                    $('#enseignants-table_paginate').addClass('flex items-center space-x-2 mt-4');
                    $('#enseignants-table_filter').addClass('flex items-center space-x-4 mt-4');
                }
            });
        });
    </script>
</x-app-layout>
