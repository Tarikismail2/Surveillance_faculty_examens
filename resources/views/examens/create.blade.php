<!-- resources/views/examens/create.blade.php -->
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
                        <label for="date" class="block text-gray-700 dark:text-gray-300">Date</label>
                        <input type="date" name="date" id="date" class="form-control mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div class="form-group mb-4">
                        <label for="heure_debut" class="block text-gray-700 dark:text-gray-300">Heure de Début</label>
                        <input type="time" name="heure_debut" id="heure_debut" class="form-control mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div class="form-group mb-4">
                        <label for="heure_fin" class="block text-gray-700 dark:text-gray-300">Heure de Fin</label>
                        <input type="time" name="heure_fin" id="heure_fin" class="form-control mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div class="form-group mb-4">
                        <label for="id_salle" class="block text-gray-700 dark:text-gray-300">Salle</label>
                        <select name="id_salle" id="id_salle" class="form-control mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @foreach($salles as $salle)
                            <option value="{{ $salle->id }}">{{ $salle->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="form-group mb-4">
                        <label for="id_department" class="block text-gray-700 dark:text-gray-300">Departement</label>
                        <select name="id_department" id="id_department" class="form-control mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @foreach($departments as $department)
                            <option value="{{ $department->id_department }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="form-group mb-4">
                        <label for="id_module" class="block text-gray-700 dark:text-gray-300">Module</label>
                        <select name="id_module" id="id_module" class="form-control mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @foreach($modules as $module)
                            <option value="{{ $module->id }}">{{ $module->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label for="id_enseignant" class="block text-gray-700 dark:text-gray-300">Enseignant</label>
                        <select name="id_enseignant" id="id_enseignant" class="form-control mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @foreach($enseignants as $enseignant)
                            <option value="{{ $enseignant->id }}">{{ $enseignant->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        {{ __('Créer') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
