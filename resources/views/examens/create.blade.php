<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer un nouvel examen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('examens.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="id_module" class="block text-gray-700 dark:text-gray-300">Module</label>
                        <select name="id_module" id="id_module" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach($modules as $module)
                                <option value="{{ $module->id }}">{{ $module->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label for="id_salle" class="block text-gray-700 dark:text-gray-300">Salle</label>
                        <select name="id_salle" id="id_salle" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach($salles as $salle)
                                <option value="{{ $salle->id }}">{{ $salle->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label for="id_enseignant" class="block text-gray-700 dark:text-gray-300">Enseignant</label>
                        <select name="id_enseignant" id="id_enseignant" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach($enseignants as $enseignant)
                                <option value="{{ $enseignant->id }}">{{ $enseignant->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label for="id_session" class="block text-gray-700 dark:text-gray-300">Session</label>
                        <select name="id_session" id="id_session" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}">{{ $session->type }} ({{ $session->date_debut }} - {{ $session->date_fin }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label for="date" class="block text-gray-700 dark:text-gray-300">Date</label>
                        <input type="date" name="date" id="date" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="form-group mb-4">
                        <label for="heure_debut" class="block text-gray-700 dark:text-gray-300">Heure de Début</label>
                        <input type="time" name="heure_debut" id="heure_debut" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="form-group mb-4">
                        <label for="heure_fin" class="block text-gray-700 dark:text-gray-300">Heure de Fin</label>
                        <input type="time" name="heure_fin" id="heure_fin" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('examens.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> {{ __('Retour') }}
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                            <i class="fas fa-save mr-2"></i> {{ __('Créer') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
