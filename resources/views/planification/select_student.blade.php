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

                    <div>
                        <label for="id_session" class="block text-sm font-medium text-gray-700">Session :</label>
                        <select name="id_session" id="id_session" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @foreach ($sessions as $id => $session)
                                <option value="{{ $id }}" {{ old('id_session', $selectedSession) == $id ? 'selected' : '' }}>{{ $session }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="id_etudiant" class="block text-sm font-medium text-gray-700">Étudiant :</label>
                        <select name="id_etudiant" id="id_etudiant" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @foreach ($students as $id => $student)
                                <option value="{{ $id }}" {{ old('id_etudiant', $selectedStudent) == $id ? 'selected' : '' }}>{{ $student }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                            Afficher l'emploi du temps
                        </button>
                    </div>
                </form>

                @if(!empty($examens) && $examens->count())
                    <div class="mt-6">
                        <h2 class="text-lg font-medium text-gray-900">Emploi du temps des examens</h2>
                        <table class="min-w-full divide-y divide-gray-200 mt-4">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure Début</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure Fin</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($examens as $examen)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $examen->date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $examen->heure_debut }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $examen->heure_fin }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $examen->module->lib_elp }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="mt-6 text-gray-500">Aucun examen trouvé pour cet étudiant et cette session.</p>
                @endif
                           
                <form action="{{ route('downloadStudentSchedulePDF') }}" method="GET" class="mt-6">
                    <input type="hidden" name="id_session" value="{{ $selectedSession }}">
                    <input type="hidden" name="id_etudiant" value="{{ $selectedStudent }}">
                    <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                        Télécharger en PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
