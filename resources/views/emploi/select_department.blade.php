<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-blue-100 p-4 rounded-lg shadow-lg">
            <h2 class="font-semibold text-xl text-blue-900 leading-tight">
                @if (isset($schedule) && !$schedule->isEmpty())
                    Emploi du temps pour le département {{ $departement->name }}
                @else
                    Sélectionnez un département et une session pour afficher l'emploi du temps
                @endif
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-lg p-6">
                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Erreur :</strong>
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Display success message -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Form for selecting department and session -->
                <form action="{{ route('displayScheduleByDepartment') }}" method="GET">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="id_department" class="block text-sm font-medium text-gray-700">Département</label>
                            <select id="id_department" name="id_department" class="form-select mt-1 block w-full">
                                <option value="" disabled selected>Choisissez un département</option>
                                @foreach ($departements as $id => $name)
                                    <option value="{{ $id }}" {{ request('id_department') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="id_session" class="block text-sm font-medium text-gray-700">Session</label>
                            <select id="id_session" name="id_session" class="form-select mt-1 block w-full" required>
                                <option value="" disabled selected>Choisissez une session</option>
                                @foreach ($sessions as $session)
                                <option value="{{ $session->id }}">
                                    {{ $session->type }} ({{ \Carbon\Carbon::parse($session->date_debut)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($session->date_fin)->format('d/m/Y') }})
                                </option>
                            @endforeach                            
                            </select>
                        </div>                        
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-md transition duration-300 hover:bg-blue-600">
                            <i class="fas fa-search mr-2"></i>
                            Afficher l'emploi du temps
                        </button>
                    </div>
                </form>

                <!-- Display schedule if available -->
                @isset($schedule)
                    @if (!$schedule->isEmpty())
                    <div class="overflow-x-auto mt-6">
                        <div class="flex justify-end mb-4 space-x-4"> <!-- Ajout de space-x-4 pour espacer les boutons -->
                            <!-- Formulaire d'envoi d'email -->
                            <form action="{{ route('sendEmailsByDepartment') }}" method="POST" id="emailForm">
                                @csrf
                                <input type="hidden" name="id_department" value="{{ request('id_department') }}">
                                <input type="hidden" name="id_session" value="{{ request('id_session') }}">
                                <button type="submit" class="flex items-center bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Envoyer l'emploi du temps par email
                                    <i class="fas fa-spinner fa-spin ml-2 hidden" id="loadingIcon"></i> <!-- Icône de chargement -->
                                </button>
                            </form>
                    
                            <form action="{{ route('download-schedule', ['id_department' => request('id_department'), 'id_session' => request('id_session')]) }}" method="GET">
                                @csrf
                                <button type="submit" class="flex items-center bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out">
                                    <i class="fas fa-download mr-2"></i>
                                    Télécharger l'emploi du temps sous forme PDF
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                        <div class="mt-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                            <p>Aucune donnée disponible pour le département et la session sélectionnés.</p>
                        </div>
                    @endif
                @endisset
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Script pour activer Select2 -->
    <script>
        $(document).ready(function() {
            // Appliquer Select2 à la liste déroulante des enseignants
            $('#id_session').select2({
                placeholder: "@lang('Choisir une session')", // Placeholder par défaut
                allowClear: true // Permet de désélectionner
            });
            $('#id_department').select2({
                placeholder: "@lang('Choisir une département')", // Placeholder par défaut
                allowClear: true // Permet de désélectionner
            });

            // Afficher l'icône de chargement lors de l'envoi de l'email
            $('#emailForm').on('submit', function() {
                $('#loadingIcon').removeClass('hidden'); // Afficher l'icône de chargement
            });
        });
    </script>
</x-app-layout>
