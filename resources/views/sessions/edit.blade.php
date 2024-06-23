<!-- resources/views/sessions/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier la session') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('sessions.update', $session->id_session) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-4">
                        <label for="type" class="block text-gray-700 dark:text-gray-300">Type</label>
                        <input type="text" name="type" id="type" class="form-control mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ $session->type }}">
                    </div>
                    <div class="form-group mb-4">
                        <label for="date_debut" class="block text-gray-700 dark:text-gray-300">Date de Début</label>
                        <input type="date" name="date_debut" id="date_debut" class="form-control mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ $session->date_debut }}">
                    </div>
                    <div class="form-group mb-4">
                        <label for="date_fin" class="block text-gray-700 dark:text-gray-300">Date de Fin</label>
                        <input type="date" name="date_fin" id="date_fin" class="form-control mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ $session->date_fin }}">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        {{ __('Mettre à jour') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
