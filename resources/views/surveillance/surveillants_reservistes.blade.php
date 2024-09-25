<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Liste des Surveillants Réservistes') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- Formulaire pour sélectionner la date, la demi-journée et la session -->
                <form action="{{ route('surveillants_reservistes.index') }}" method="GET">
                    <div class="mb-4">
                        <label for="date" class="block text-sm font-medium text-gray-700">Sélectionnez la date
                            :</label>
                        <input type="date" name="date" id="date" value="{{ request('date') }}"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="demi_journee" class="block text-sm font-medium text-gray-700">Sélectionnez la
                            demi-journée :</label>
                        <select name="demi_journee" id="demi_journee"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="matin" {{ request('demi_journee') == 'matin' ? 'selected' : '' }}>Matin
                            </option>
                            <option value="apres-midi" {{ request('demi_journee') == 'apres-midi' ? 'selected' : '' }}>
                                Après-midi</option>
                        </select>
                    </div>

                    <!-- Ajout du champ de sélection pour la session -->
                    <div class="mb-4">
                        <label for="session" class="block text-sm font-medium text-gray-700">Sélectionnez la session
                            :</label>
                        <select name="session" id="session"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">Choisissez une session</option>
                            @foreach ($sessions as $session)
                                <option value="{{ $session->id }}"
                                    {{ request('session') == $session->id ? 'selected' : '' }}>
                                    {{ $session->type }} ({{ $session->date_debut }} - {{ $session->date_fin }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex space-x-4">
                        <!-- Bouton pour afficher la liste des surveillants réservistes -->
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out flex items-center space-x-2">
                            <i class="fas fa-search"></i>
                            <span>Afficher la Liste</span>
                        </button>

                        <!-- Bouton pour télécharger le PDF -->
                        <button type="submit" formaction="{{ route('surveillants_reservistes.download') }}"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out flex items-center space-x-2">
                            <i class="fas fa-download"></i>
                            <span>Télécharger PDF</span>
                        </button>
                    </div>
                </form>

                <!-- Affichage de la liste des réservistes -->
                @if (isset($reservistes) && count($reservistes) > 0)
                    <div class="mt-8">
                        <h2 class="text-lg font-semibold text-gray-900">Surveillants Réservistes pour
                            {{ $date }} - {{ ucfirst($demi_journee) }} - Session: {{ $sessionName }}</h2>
                        <div class="overflow-x-auto mt-4">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-gray-700 bg-gray-100">
                                            Nom Complet</th>
                                        <th
                                            class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-gray-700 bg-gray-100">
                                            Département</th>
                                        <th
                                            class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-gray-700 bg-gray-100">
                                            Email</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach ($reservistes as $reserviste)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                {{ $reserviste->enseignant->name }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                {{ $reserviste->enseignant->department->name }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                {{ $reserviste->enseignant->email }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="mt-8">
                        <p class="text-gray-500">Aucun surveillant trouvé pour cette date, cette demi-journée, et cette
                            session.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
