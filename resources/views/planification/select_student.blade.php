<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sélectionnez un étudiant et une session') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('displayStudentSchedule') }}" method="GET" class="space-y-6">
                    @csrf

                    <!-- Sélection de la session -->
                    <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                        <label for="id_session" class="block text-sm font-medium text-gray-700">Session :</label>
                        <div class="relative">
                            <select name="id_session" id="id_session"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @foreach ($sessions as $id => $session)
                                    <option value="{{ $id }}"
                                        {{ old('id_session', $selectedSession) == $id ? 'selected' : '' }}>
                                        {{ $session }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Entrée du CNE -->
                    <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                        <label for="cne" class="block text-sm font-medium text-gray-700">CNE :</label>
                        <div class="relative">
                            <input type="text" id="cne" name="cne"
                                class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                value="{{ old('cne') }}">
                            @error('cne')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Sélection de l'étudiant -->
                    <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                        <label for="id_etudiant" class="block text-sm font-medium text-gray-700">Étudiant :</label>
                        <div class="relative">
                            <select name="id_etudiant" id="id_etudiant"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @foreach ($students as $id => $fullName)
                                    <option value="{{ $id }}" {{ old('id_etudiant', $selectedStudent) == $id ? 'selected' : '' }}>
                                        {{ $fullName }}</option>
                                @endforeach
                            </select>
                            @error('id_etudiant')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                            <i class="fas fa-eye"></i> Afficher l'emploi du temps
                        </button>
                    </div>
                </form>

                @if (!empty($examens))
                    <div class="mt-6 bg-gray-50 p-4 rounded-lg shadow-sm">
                        <h2 class="text-lg font-medium text-gray-900">Emploi du temps de : {{ $etudiant->nom ?? 'Inconnu' }} {{ $etudiant->prenom ?? '' }}</h2>
                        <form action="{{ route('downloadStudentSchedulePDF') }}" method="GET" class="mt-6">
                            <input type="hidden" name="id_session" value="{{ $selectedSession }}">
                            <input type="hidden" name="id_etudiant" value="{{ $selectedStudent }}">
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                                <i class="fas fa-file-download"></i> Télécharger en PDF
                            </button>
                        </form>
                    </div>
                @else
                    <p class="mt-6 text-gray-500">Aucun examen trouvé pour cet étudiant et cette session.</p>
                @endif
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Appliquer Select2 à la liste déroulante des étudiants
            $('#id_etudiant').select2({
                placeholder: "Choisir un étudiant", // Placeholder par défaut
                allowClear: true // Permet de désélectionner
            });
        });
    </script>
</x-app-layout>
