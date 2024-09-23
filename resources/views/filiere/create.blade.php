<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-2xl text-black-600 leading-tight">
                {{ __('Créer une Filière') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
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

                <form action="{{ route('filiere.store') }}" method="POST">
                    @csrf

                    <div class="flex mb-6">
                        <!-- Left Part -->
                        <div class="flex-1 mr-4">
                            <div class="mb-4">
                                <label for="code_etape" class="block text-gray-700 text-sm font-bold mb-2">Code Étape</label>
                                <input type="text" id="code_etape" name="code_etape"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label for="version_etape" class="block text-gray-700 text-sm font-bold mb-2">Nom de la Filière</label>
                                <input type="text" id="version_etape" name="version_etape"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label for="id_session" class="block text-gray-700 text-sm font-bold mb-2">Session</label>
                                <select id="id_session" name="id_session"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <option value="">Sélectionnez une session</option>
                                    @foreach($sessions as $session)
                                        <option value="{{ $session->id }}">{{ $session->type }}  ({{$session->date_debut}} - {{$session->date_fin}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Right Part -->
                        <div class="flex-1 ml-4">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Recherche de Filières</label>
                                <input type="text" id="search_filiere" placeholder="Rechercher une filière..."
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>

                            <div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">Sélectionnez les Filières</label>
    <div id="filiere_list" style="width: 100%; height: 300px; overflow-y: auto; border: 1px solid #d1d5db;">
        <div class="grid grid-cols-1 gap-4 p-2">
            @foreach($filieres as $filiere)
                <div class="flex items-center filiere-item">
                    <input type="checkbox" id="filiere_{{ $filiere->code_etape }}" name="filieres[]" value="{{ $filiere->code_etape }}"
                        class="mr-2 leading-tight">
                    <label for="filiere_{{ $filiere->code_etape }}" class="text-gray-700">
                        {{ $filiere->version_etape }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</div>

                        </div>
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

    <script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search_filiere');
    const filiereItems = document.querySelectorAll('.filiere-item');

    function normalizeString(str) {
        return str.normalize('NFD').replace(/[\u0300-\u036f]/g, "").toLowerCase();
    }

    searchInput.addEventListener('input', function () {
        const searchTerm = normalizeString(searchInput.value);

        filiereItems.forEach(function (item) {
            const label = normalizeString(item.querySelector('label').textContent);
            if (label.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

    </script>
</x-app-layout>