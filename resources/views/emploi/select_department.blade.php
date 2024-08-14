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

                <!-- Form for selecting department and session -->
                <form action="{{ route('displayScheduleByDepartment') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="id_department"
                                class="block text-sm font-medium text-gray-700">Département</label>
                            <select id="id_department" name="id_department" class="form-select mt-1 block w-full">
                                <option value="" disabled selected>Choisissez un département</option>
                                @foreach ($departements as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ request('id_department') == $id ? 'selected' : '' }}>{{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="id_session" class="block text-sm font-medium text-gray-700">Session</label>
                            <select id="id_session" name="id_session" class="form-select mt-1 block w-full">
                                <option value="" disabled selected>Choisissez une session</option>
                                @foreach ($sessions as $id => $type)
                                    <option value="{{ $id }}"
                                        {{ request('id_session') == $id ? 'selected' : '' }}>{{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">Afficher l'emploi du
                            temps</button>
                    </div>
                </form>

       <!-- Display schedule if available -->
@isset($schedule)
@if(!$schedule->isEmpty())
    <div class="overflow-x-auto mt-6">
        <div class="flex justify-end mb-4">
            <a href="{{ route('download-schedule', ['id_department' => $idDepartment, 'id_session' => $idSession]) }}" 
                class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
               Télécharger le planning
            </a>
        </div>
        <table class="min-w-full divide-y divide-gray-200 bg-white shadow-md rounded-lg">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Heure de début</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Heure de fin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Salle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Enseignant</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($schedule as $entry)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $entry->examen->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $entry->examen->heure_debut->format('H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $entry->examen->heure_fin->format('H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $entry->salle->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $entry->enseignant->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="mt-4 text-center text-gray-500">Aucun emploi du temps disponible pour le département sélectionné et la session choisie.</p>
@endif
@endisset


            </div>
        </div>
</x-app-layout>
