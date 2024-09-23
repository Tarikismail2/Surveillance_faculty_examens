<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-green-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-green-800 leading-tight">
                {{ __('Gestion des Étudiants') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6 border border-gray-200">

                <!-- Display Error Message -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        @foreach ($errors->all() as $error)
                            <span class="block sm:inline">{{ $error }}</span>
                        @endforeach
                    </div>
                @endif
                
                <!-- Formulaire de création d'étudiant -->
                <form action="{{ route('etudiants.store', ['id_module' => $modules->id]) }}" method="POST">
                @csrf

                    <div class="flex justify-between">
                        <!-- First Div: Inputs for Student Details -->
                        <div class="w-full md:w-1/2">
                            <div class="mb-4">
                                <label for="nom" class="block text-gray-700">Nom</label>
                                <input type="text" name="nom" id="nom" class="w-full px-3 py-2 border rounded-lg"  placeholder="Nom" required>
                            </div>

                            <div class="mb-4">
                                <label for="prenom" class="block text-gray-700">Prénom</label>
                                <input type="text" name="prenom" id="prenom" class="w-full px-3 py-2 border rounded-lg"  placeholder="Prénom" required>
                            </div>

                            <div class="mb-4">
                                <label for="code" class="block text-gray-700">Code</label>
                                <input type="text" name="code_etudiant" id="code_etudiant" class="w-full px-3 py-2 border rounded-lg" placeholder="code_etudiant"required>
                            </div>


                            <div class="mb-4">
                                <label for="cin" class="block text-gray-700">CIN</label>
                                <input type="text" name="cin" id="cin" class="w-full px-3 py-2 border rounded-lg"  placeholder="CIN" required>
                            </div>

                            <div class="mb-4">
                                <label for="cne" class="block text-gray-700">CNE</label>
                                <input type="text" name="cne" id="cne" class="w-full px-3 py-2 border rounded-lg"  placeholder="CNE" required>
                            </div>
                            
                            
                        </div>

                        <!-- Second Div: Disabled Inputs -->
                        <div class="w-full md:w-1/2 pl-6">
                            <div class="mb-4">
                                <label for="disabled_input_1" class="block text-gray-700">Session des examnes</label>
                                <input type="text" id="disabled_input_1" class="w-full px-3 py-2 border rounded-lg bg-gray-200 font-bold" disabled value="{{$session->type}}">
                            </div>

                            <div class="mb-4">
                                <label for="disabled_input_2" class="block text-gray-700">Filiere</label>
                                <input type="text" id="disabled_input_2" class="w-full px-3 py-2 border rounded-lg bg-gray-200 font-bold" disabled value="{{$filiere->version_etape}}">
                            </div>
                            <div class="mb-4">
                                <label for="disabled_input_2" class="block text-gray-700">Module</label>
                                <input type="text" id="disabled_input_2" class="w-full px-3 py-2 border rounded-lg bg-gray-200 font-bold" disabled value="{{$modules->lib_elp}}">
                            </div>
                            <div class="mb-4">
                                <label for="date_naissance" class="block text-gray-700">Date de Naissance</label>
                                <input type="date" name="date_naissance" id="date_naissance" class="w-full px-3 py-2 border rounded-lg" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-4">
                        {{ __('Créer') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
