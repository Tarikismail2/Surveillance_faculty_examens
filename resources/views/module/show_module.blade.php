<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails du Module') }} : {{ $module->lib_elp }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between">
            <!-- First Column: Module Information -->
            <div class="w-1/2">

                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Nom du Module</h3>
                    <p class="text-lg text-gray-600">{{ $module->lib_elp }}</p>
                </div>
            </div>

            <!-- Second Column: Add Student Button -->
            <div class="w-1/2 text-right">
                <a href="{{ route('etudiants.create', ['id_module' => $module->id ]) }}" 
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Ajouter Étudiant  
                </a>
                <a href="{{ route('etudiant.import', ['id_module' => $module->id ]) }}" 
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" style="background-color: blue;">
                    Importer plusieurs etudiants
                </a>
            </div>
            
        </div>

                <!-- Table DataTables pour les Étudiants Inscrits -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-700 mb-4">Étudiants Inscrits</h4>
                    <table id="students-table" class="display w-full">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Les données seront chargées via AJAX -->
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
            $('#students-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('modules.students',[ $module->lib_elp , $module->code_etape ]) }}',
                columns: [
                    { data: 'nom', name: 'nom' },
                    { data: 'prenom', name: 'prenom' }
                ]
            });
        });
    </script>
</x-app-layout>
