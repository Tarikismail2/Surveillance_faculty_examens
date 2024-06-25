<!-- resources/views/examens/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier un Examen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('examens.update', $examen->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Champ pour la date -->
                    <div class="mb-4">
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="date" id="date" value="{{ old('date', $examen->date) }}" class="form-input mt-1 block w-full">
                    </div>

                    <!-- Champ pour l'heure de début -->
                    <div class="mb-4">
                        <label for="heure_debut" class="block text-sm font-medium text-gray-700">Heure de Début</label>
                        <input type="time" name="heure_debut" id="heure_debut" value="{{ old('heure_debut', $examen->heure_debut) }}" class="form-input mt-1 block w-full">
                    </div>

                    <!-- Champ pour l'heure de fin -->
                    <div class="mb-4">
                        <label for="heure_fin" class="block text-sm font-medium text-gray-700">Heure de Fin</label>
                        <input type="time" name="heure_fin" id="heure_fin" value="{{ old('heure_fin', $examen->heure_fin) }}" class="form-input mt-1 block w-full">
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
