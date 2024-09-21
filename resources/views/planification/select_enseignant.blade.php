<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-blue-100 p-4 rounded-lg shadow-lg">
            <h2 class="font-semibold text-xl text-blue-900 leading-tight">
                Sélectionnez un enseignant pour afficher son emploi du temps
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-lg p-6">
                <!-- Affichage des erreurs -->
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

                <!-- Affichage du message de succès -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Formulaire de sélection de la session et de l'enseignant -->
                <form action="{{ route('displaySchedule') }}" method="GET">
                    @csrf

                    <div class="mb-6">
                        <label for="id_session" class="block text-sm font-medium text-gray-700">Session :</label>
                        <select name="id_session" id="id_session" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @foreach ($sessions as $id => $session)
                                <option value="{{ $id }}" {{ old('id_session') == $id ? 'selected' : '' }}>
                                    {{ $session }} 
                                    {{-- ({{ $sessionDates[$id]['start'] }} - {{ $sessionDates[$id]['end'] }}) --}}
                                </option>
                            @endforeach
                        </select>
                        @error('id_session')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="enseignant_id" class="block text-sm font-medium text-gray-700">Enseignant :</label>
                        <select name="id_enseignant" id="enseignant_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @foreach ($enseignants as $id => $enseignant)
                                <option value="{{ $id }}" {{ old('id_enseignant') == $id ? 'selected' : '' }}>
                                    {{ $enseignant }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_enseignant')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email :</label>
                        <input type="email" id="email" name="email" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                        <i class="fas fa-search"></i> Afficher l'emploi du temps
                    </button>
                </form>

                @if(isset($schedule))
                    <div class="mt-8">
                        <h3 class="font-semibold text-lg text-gray-800 mb-4">Emploi du temps pour {{ $selectedEnseignant }} :</h3>
                        <form action="{{ route('downloadSurveillancePDF') }}" method="POST" class="mt-6">
                            @csrf
                            <input type="hidden" name="id_session" value="{{ $id_session }}">
                            <input type="hidden" name="id_enseignant" value="{{ $selectedEnseignantId }}">
                            <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                                <i class="fas fa-download"></i> Télécharger PDF
                            </button>
                        </form>
                    </div>
                @endif
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
            $('#enseignant_id').select2({
                placeholder: "@lang('Choisir un enseignant')", // Placeholder par défaut
                allowClear: true // Permet de désélectionner
            });
        });
    </script>
</x-app-layout>
