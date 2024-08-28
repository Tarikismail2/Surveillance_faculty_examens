<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Enseignants') }}
            </h2>
            <a href="{{ route('enseignants.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 00-2 0v3H6a1 1 0 000 2h3v3a1 1 0 002 0v-3h3a1 1 0 000-2h-3V7z"
                        clip-rule="evenodd" />
                </svg>
                {{ __('Créer un nouvel enseignant') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    <!-- Messages de succès et d'erreur -->
                    @if (session('status'))
                        <div
                            class="mb-4 p-4 rounded-md {{ session('status')['type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ session('status')['message'] }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table id="enseignants-table" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        {{ __('Nom') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        {{ __('Email') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        {{ __('Département') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
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

    <!-- DataTables Scripts -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">

    <script>
        $(document).ready(function() {
            $('#enseignants-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('enseignants.index') }}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'department_name',
                        name: 'department_name'
                    },
                    {
                        data: null,
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <a href="/enseignants/${data.id}/edit" class="text-yellow-600 hover:text-yellow-700 font-medium" title="Modifier">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L5 12.172V15h2.828l9.586-9.586a2 2 0 000-2.828zM4 13H3v4a1 1 0 001 1h4v-1H4v-3z" />
                                    </svg>
                                </a>
                                <form action="/enseignants/${data.id}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet enseignant ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium" title="Supprimer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 8a1 1 0 011-1h6a1 1 0 011 1v9a1 1 0 11-2 0v-1H8v1a1 1 0 11-2 0V8zm3-3a1 1 0 00-1-1V3a1 1 0 112 0v1a1 1 0 00-1 1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            `;
                        }
                    }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/French.json"
                },
                initComplete: function() {
                    $('#enseignants-table_paginate .paginate_button').addClass(
                        'py-2 px-4 border rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300');
                    $('#enseignants-table_paginate .paginate_button.current').addClass(
                        'bg-blue-600 text-white');
                    $('#enseignants-table_info').addClass('text-gray-700 text-sm');
                    $('#enseignants-table_filter input').addClass(
                        'border border-gray-300 rounded-lg py-2 px-4');
                    $('#enseignants-table_length select').addClass(
                        'border border-gray-300 rounded-lg py-2 px-4');
                    $('#enseignants-table_processing').addClass(
                        'text-gray-700 font-medium bg-gray-100 p-2 rounded-lg');
                    $('#enseignants-table_paginate').addClass('flex items-center space-x-2 mt-4');
                    $('#enseignants-table_filter').addClass('flex items-center space-x-4 mt-4');
                }
            });
        });
    </script>
</x-app-layout>
