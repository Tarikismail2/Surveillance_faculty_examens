<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer une nouvelle session') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('sessions.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="type" class="block text-gray-700 dark:text-gray-300">Type</label>
                        <select name="type" id="type" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="S_N_1">Session Normale 1er semestre</option>
                            <option value="S_N_2">Session Normale 2eme semestre</option>
                            <option value="S_R_1">Session Rattrapage 1er semestre</option>
                            <option value="S_R_2">Session Rattrapage 2eme semestre</option>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label for="date_debut" class="block text-gray-700 dark:text-gray-300">Date de Début</label>
                        <input type="date" name="date_debut" id="date_debut" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="form-group mb-4">
                        <label for="date_fin" class="block text-gray-700 dark:text-gray-300">Date de Fin</label>
                        <input type="date" name="date_fin" id="date_fin" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('sessions.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
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
