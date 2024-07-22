<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-blue-100 p-4 rounded-lg shadow-lg">
            <h2 class="font-semibold text-xl text-blue-900 leading-tight">
                Sélectionnez un étudiant pour afficher son emploi du temps
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-lg p-6">
                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg shadow-md" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <form action="{{ route('displayStudentSchedule') }}" method="GET">
                    @csrf

                    <div class="mb-6">
                        <label for="id_session" class="block text-sm font-medium text-gray-700">Session :</label>
                        <select name="id_session" id="id_session" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @foreach ($sessions as $id => $session)
                                <option value="{{ $id }}" {{ old('id_session') == $id ? 'selected' : '' }}>{{ $session }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="id_etudiant" class="block text-sm font-medium text-gray-700">Étudiant :</label>
                        <select name="id_etudiant" id="id_etudiant" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @foreach ($students as $id => $student)
                                <option value="{{ $id }}" {{ old('id_etudiant') == $id ? 'selected' : '' }}>{{ $student }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                        Afficher l'emploi du temps
                    </button>
                </form>

                @if(isset($schedule))
                    <div class="mt-8">
                        <h3 class="font-semibold text-lg text-gray-800 mb-4">Emploi du temps pour {{ $selectedStudent }} :</h3>
                        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                            <thead>
                                <tr>
                                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-100 text-gray-800">Date</th>
                                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-100 text-gray-800">Heure de début</th>
                                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-100 text-gray-800">Heure de fin</th>
                                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-100 text-gray-800">Salle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedule->sortBy(['examen.date', 'examen.heure_debut']) as $item)
                                    <tr>
                                        <td class="py-3 px-4 border-b border-gray-200">{{ $item->examen->date }}</td>
                                        <td class="py-3 px-4 border-b border-gray-200">{{ $item->examen->heure_debut }}</td>
                                        <td class="py-3 px-4 border-b border-gray-200">{{ $item->examen->heure_fin }}</td>
                                        <td class="py-3 px-4 border-b border-gray-200">{{ $item->salle->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <form action="{{ route('downloadStudentSchedulePDF') }}" method="POST" class="mt-6">
                            @csrf
                            <input type="hidden" name="id_session" value="{{ $id_session }}">
                            <input type="hidden" name="id_etudiant" value="{{ $selectedStudentId }}">
                            <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                                Télécharger PDF
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
