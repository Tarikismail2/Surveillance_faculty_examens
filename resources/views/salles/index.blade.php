<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Salles') }}
            </h2>
            <a href="{{ route('salles.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                <i class="fas fa-plus"></i>
                <span class="ml-2">{{ __('Ajouter un nouvelle salle') }}</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    @if (session('status'))
                        <div class="mb-4 p-4 rounded-md {{ session('status')['type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ session('status')['message'] }}
                        </div>
                    @endif
                    <div class="overflow-x-auto">
                        <table id="sallesTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('ID') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Nom') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Capacité') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($salles as $salle)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $salle->id }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $salle->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $salle->capacite }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium action-icons">
                                            <a href="{{ route('salles.edit', $salle->id) }}" title="{{ __('Modifier') }}" class="text-yellow-600 hover:text-yellow-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L5 12.172V15h2.828l9.586-9.586a2 2 0 000-2.828zM4 13H3v4a1 1 0 001 1h4v-1H4v-3z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('salles.destroy', $salle->id) }}" method="POST" class="inline-block" onclick="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cet salle ?') }}');">
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
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ __('Aucune salle trouvée.') }}
                                        </td>
                                    </tr>
                                @endforelse
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
            $('#sallesTable').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/French.json"
                },
                initComplete: function () {
                    $('#sallesTable_paginate .paginate_button').addClass('py-2 px-4 border rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300');
                    $('#sallesTable_paginate .paginate_button.current').addClass('bg-blue-600 text-white');
                    $('#sallesTable_info').addClass('text-gray-700 text-sm');
                    $('#sallesTable_filter input').addClass('border border-gray-300 rounded-lg py-2 px-4');
                    $('#sallesTable_length select').addClass('border border-gray-300 rounded-lg py-2 px-4');
                    $('#sallesTable_processing').addClass('text-gray-700 font-medium bg-gray-100 p-2 rounded-lg');
                    $('#sallesTable_paginate').addClass('flex items-center space-x-2 mt-4');
                    $('#sallesTable_filter').addClass('flex items-center space-x-4 mt-4');
                }
            });
        });
    </script>
</x-app-layout>
