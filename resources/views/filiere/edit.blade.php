<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-2xl text-black-600 leading-tight">
                {{ __('Modifier une Filière') }}
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

                <form action="{{ route('filiere.update', $filiere->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="code_etape" class="block text-gray-700 text-sm font-bold mb-2">Code Étape</label>
                        <input type="text" id="code_etape" name="code_etape"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            value="{{ $filiere->code_etape }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="version_etape" class="block text-gray-700 text-sm font-bold mb-2">Nom de la Filière</label>
                        <input type="text" id="version_etape" name="version_etape"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            value="{{ $filiere->version_etape }}" required>
                    </div>

                    <!-- Add the id_session field here -->
                    <div class="mb-4">
                        <label for="id_session" class="block text-gray-700 text-sm font-bold mb-2">Session</label>
                        <select id="id_session" name="id_session"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="" disabled selected>Choisissez une session</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}" {{ $filiere->id_session == $session->id ? 'selected' : '' }}>
                                    {{ $session->type }}  ({{$session->date_debut}} - {{$session->date_fin}})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-button>
                            {{ __('Mettre à Jour') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
