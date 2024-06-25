<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails de la Session') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="ml-4 text-lg leading-7 font-semibold">
                                Session ID: {{ $session->id_session }}
                            </div>
                        </div>
                        <div class="flex items-center">
                            <a href="{{ route('sessions.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 hover:text-gray-900 rounded-md py-2 px-4 transition-colors duration-300 ease-in-out">
                                Retour à la liste
                            </a>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="ml-12 mt-6">
                        <div class="flex">
                            <div class="w-1/3">
                                <label class="block font-semibold mb-1">Type:</label>
                                <p class="text-gray-800">{{ $session->type }}</p>
                            </div>
                            <div class="w-1/3">
                                <label class="block font-semibold mb-1">Date de Début:</label>
                                <p class="text-gray-800">{{ $session->date_debut }}</p>
                            </div>
                            <div class="w-1/3">
                                <label class="block font-semibold mb-1">Date de Fin:</label>
                                <p class="text-gray-800">{{ $session->date_fin }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="mt-8">
                        <a href="{{ route('examens.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Planifier des Examens
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
