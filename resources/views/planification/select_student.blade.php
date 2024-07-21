<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Sélectionnez un étudiant pour afficher son emploi du temps
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <form action="{{ route('displayStudentSchedule') }}" method="GET">
                    @csrf

                    <div class="mb-4">
                        <label for="id_session" class="block text-sm font-medium text-gray-700">Session :</label>
                        <select name="id_session" id="id_session" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach ($sessions as $id => $session)
                                <option value="{{ $id }}" {{ old('id_session') == $id ? 'selected' : '' }}>{{ $session }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="id_etudiant" class="block text-sm font-medium text-gray-700">Étudiant :</label>
                        <select name="id_etudiant" id="id_etudiant" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="" disabled selected>Choisir un nom</option>
                            @foreach ($students as $id => $student)
                                <option value="{{ $id }}" {{ old('id_etudiant') == $id ? 'selected' : '' }}>{{ $student }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Afficher l'emploi du temps
                    </button>
                </form>

                @if(isset($schedule))
                    <div class="mt-6">
                        <h3 class="font-semibold text-lg text-gray-800">Emploi du temps pour {{ $selectedStudent }} :</h3>
                        <table class="min-w-full bg-white mt-4">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50">Date</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50">Heure de début</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50">Heure de fin</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50">Salle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedule as $item)
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ $item->date }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ $item->heure_debut }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ $item->heure_fin }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ $item->salle->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <form action="{{ route('downloadStudentSchedulePDF') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="id_session" value="{{ $id_session }}">
                            <input type="hidden" name="id_etudiant" value="{{ $selectedStudentId }}">
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Télécharger PDF
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Include Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#id_etudiant').select2({
                placeholder: "Choisir un nom",
                allowClear: true,
                language: {
                    inputTooShort: function () {
                        return 'Tapez au moins un caractère';
                    }
                }
            });
        });
    </script>
</x-app-layout>
