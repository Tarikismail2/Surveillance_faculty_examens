<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-2xl text-black-600 leading-tight">
                {{ __('Créer un module') }}
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

                <form action="{{ route('modules.store') }}" method="POST">
    @csrf
    <div class="flex mb-6">
        <!-- Left Part -->
        <div class="flex-1 mr-4">
            <div class="mb-4">
                <label for="code_elp" class="block text-gray-700 text-sm font-bold mb-2">Code de module</label>
                <input type="text" id="code_elp" name="code_elp"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
            </div>
            <div class="mb-4">
                <label for="lib_elp" class="block text-gray-700 text-sm font-bold mb-2">Nom de module</label>
                <input type="text" id="lib_elp" name="lib_elp"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
            </div>            
        </div>

        <!-- Right Part -->
        <div class="flex-1 ml-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Filiere selectionnee</label>
                <input type="text" id="version_etape" value="{{$filieres->version_etape}}" 
                    disabled
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Session selectionnee</label>
                <input type="text" id="session" value="{{$session->type}}" 
                    disabled
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
            </div>
        </div>
    </div>

    <!-- Hidden inputs for passing additional data -->
    <input type="hidden" name="id_session" value="{{ $session->id }}">
    <input type="hidden" name="code_etape" value="{{ $filieres->code_etape }}">

    <div class="flex items-center justify-between">
        <x-button onclick="return confirm('Are you sure you want to create this module?')">
            {{ __('Créer') }}
        </x-button>
    </div>
</form>


            </div>
        </div>
    </div>

</x-app-layout>