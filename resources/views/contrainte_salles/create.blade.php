<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-2xl text-black-600 leading-tight">
                {{ __('Créer une Contrainte pour Salle') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        <strong class="font-bold">{{ session('success') }}</strong>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Il y a eu des problèmes avec votre saisie.</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('contrainte_salles.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="id_salle" class="block text-gray-700 text-sm font-bold mb-2">Salle</label>
                        <select id="id_salle" name="id_salle"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">Sélectionner une salle</option>
                            @foreach ($salles as $salle)
                                <option value="{{ $salle->id }}">{{ $salle->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="id_session" class="block text-gray-700 text-sm font-bold mb-2">Session</label>
                        <select id="id_session" name="id_session"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">Sélectionner une session</option>
                            @foreach ($sessions as $session)
                                <option value="{{ $session->id }}">{{ $session->type }} ({{ $session->date_debut }} - {{$session->date_fin}})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date</label>
                        <input type="date" id="date" name="date"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                    </div>

                    <div class="mb-4">
                        <label for="heure_debut" class="block text-gray-700 text-sm font-bold mb-2">Heure de
                            Début</label>
                            <select name="heure_debut" id="heure_debut"
                            class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                            <option value="">@lang('Sélectionnez une heure de début')</option>
                            <option value="08:30">08:30</option>
                            <option value="10:15">10:15</option>
                            <option value="14:30">14:30</option>
                            <option value="16:15">16:15</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="heure_fin" class="block text-gray-700 text-sm font-bold mb-2">Heure de Fin</label>
                        <select name="heure_fin" id="heure_fin"
                        class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                        <option value="">@lang('Sélectionnez une heure de fin')</option>
                        <option value="10:00">10:00</option>
                        <option value="11:45">11:45</option>
                        <option value="16:00">16:00</option>
                        <option value="17:45">17:45</option>
                    </select>
                    </div>

                    <div class="mb-4">
                        <label for="validee" class="block text-gray-700 text-sm font-bold mb-2">Validée</label>
                        <select id="validee" name="validee"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required hidden>
                            <option value="0">Non</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-button>
                            {{ __('Créer') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Script pour activer Select2 -->
    <script>
        $(document).ready(function() {
            $('#id_salle').select2({
                placeholder: "@lang('Choisir une salle')",
                allowClear: true 
            });
        });
    </script>

<script>
    $(document).ready(function() {
        $('#id_session').select2({
            placeholder: "@lang('Choisir une salle')",
            allowClear: true 
        });
    });
</script>

</x-app-layout>
