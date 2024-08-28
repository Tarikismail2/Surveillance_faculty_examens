<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Liste des contraintes des enseignants') }}
            </h2>
            <a href="{{ route('contrainte_enseignants.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                <i class="fas fa-plus"></i>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Formulaire de filtrage -->
                    <form method="GET" action="{{ route('contrainte_enseignants.index_admin') }}" class="mb-4">
                        <div class="flex items-center">
                            <label for="id_session"
                                class="block text-gray-700 text-sm font-bold mb-2 mr-4">Session</label>
                            <select id="id_session" name="id_session"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Sélectionner une session</option>
                                @foreach ($sessions as $session)
                                    <option value="{{ $session->id }}"
                                        {{ request('id_session') == $session->id ? 'selected' : '' }}>
                                        {{ $session->type }} ({{ $session->date_debut }} - {{ $session->date_fin }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit"
                                class="ml-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Filtrer
                            </button>
                        </div>
                    </form>

                    <!-- Tableau avec DataTables -->
                    <div class="overflow-x-auto">
                        <table id="constraintsTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Enseignant') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Date') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Heure de début') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Heure de fin') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Statut') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($contraintes as $contrainte)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($contrainte->enseignant)
                                                {{ $contrainte->enseignant->name }}
                                            @else
                                                {{ __('Aucun enseignant associé') }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $contrainte->date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $contrainte->heure_debut }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $contrainte->heure_fin }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $contrainte->validee ? 'Validée' : 'Non validée' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ __('Aucune contrainte trouvée.') }}
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

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <!-- jQuery -->
    <!-- DataTables Scripts -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">

    <script>
        $(document).ready(function() {
            $('#constraintsTable').DataTable();
        });
    </script>
</x-app-layout>
