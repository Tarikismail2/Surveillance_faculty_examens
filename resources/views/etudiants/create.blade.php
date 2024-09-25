<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-green-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-green-800 leading-tight">
                {{ __('Création d\'un Étudiant') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6 border border-gray-200">

                <!-- Display Error Message -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        @foreach ($errors->all() as $error)
                            <span class="block sm:inline">{{ $error }}</span>
                        @endforeach
                    </div>
                @endif

                <!-- Form for Creating Student -->
                <form action="{{ route('etudiants.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Div: Inputs for Student Details -->
                        <div>
                            <div class="mb-4">
                                <label for="nom" class="block text-gray-700">Nom</label>
                                <input type="text" name="nom" id="nom" class="w-full px-3 py-2 border rounded-lg" placeholder="Nom" required>
                            </div>

                            <div class="mb-4">
                                <label for="prenom" class="block text-gray-700">Prénom</label>
                                <input type="text" name="prenom" id="prenom" class="w-full px-3 py-2 border rounded-lg" placeholder="Prénom" required>
                            </div>

                            <div class="mb-4">
                                <label for="code_etudiant" class="block text-gray-700">Code Étudiant</label>
                                <input type="text" name="code_etudiant" id="code_etudiant" class="w-full px-3 py-2 border rounded-lg" placeholder="Code Étudiant" required>
                            </div>

                            <div class="mb-4">
                                <label for="cin" class="block text-gray-700">CIN</label>
                                <input type="text" name="cin" id="cin" class="w-full px-3 py-2 border rounded-lg" placeholder="CIN" required>
                            </div>

                            <div class="mb-4">
                                <label for="cne" class="block text-gray-700">CNE</label>
                                <input type="text" name="cne" id="cne" class="w-full px-3 py-2 border rounded-lg" placeholder="CNE" required>
                            </div>
                        </div>

                        <!-- Second Div: Additional Information -->
                        <div>
                            <div class="form-group mb-4">
                                <label for="date_naissance">Date de Naissance</label>
                                <input type="date" class="form-control w-full px-3 py-2 border rounded-lg" id="date_naissance" name="date_naissance" required>
                            </div>

                            <div class="mb-4">
                                <label for="module_search" class="block text-gray-700">Rechercher Module</label>
                                <input type="text" id="module_search" class="w-full px-3 py-2 border rounded-lg" placeholder="Rechercher un module" onkeyup="filterModules()">
                            </div>

                            <!-- Sélection des modules avec barre de recherche et scroll -->
                            <label for="modules" class="block text-gray-700 font-semibold">Modules</label>
                            <div id="modulesList" class="space-y-2 overflow-y-auto h-48 border border-gray-300 rounded-md p-2">
                                @foreach ($modules as $module)
                                    <div class="module-item">
                                        <input type="checkbox" name="modules[]" value="{{ $module->id }}" id="module_{{ $module->id }}">
                                        <label for="module_{{ $module->id }}" class="ml-2">{{ $module->lib_elp }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-4">
                                <label for="id_session" class="block text-gray-700">Session des Examens</label>
                                <select name="id_session" id="id_session" class="w-full px-3 py-2 border rounded-lg" required>
                                    <option value="">Sélectionnez une session</option>
                                    @foreach ($sessions as $session)
                                        <option value="{{ $session->id }}">{{ $session->type }} ({{ $session->date_debut }} - {{ $session->date_fin }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            Créer l'Étudiant
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function filterModules() {
            const searchInput = document.getElementById('module_search').value.toLowerCase();
            const moduleItems = document.querySelectorAll('.module-item'); // Select module items
            moduleItems.forEach(item => {
                const label = item.querySelector('label').textContent.toLowerCase(); // Get label text
                item.style.display = label.includes(searchInput) ? 'flex' : 'none'; // Show or hide
            });
        }
    </script>
</x-app-layout>
