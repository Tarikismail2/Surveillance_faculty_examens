<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-blue-100 p-4 rounded-lg shadow-lg">
            <h2 class="font-semibold text-xl text-blue-900 leading-tight">
                @if (isset($schedule) && !$schedule->isEmpty())
                    Emploi du temps pour les étudiants de la filière {{ $filiere->version_etape }}
                @else
                    Sélectionnez une session et une filière pour afficher l'emploi du temps
                @endif
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-lg p-6">
                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Erreur :</strong>
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
    
                <!-- Form for selecting department and session -->
                <form id="downloadForm">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="id_session" class="block text-sm font-medium text-gray-700">Session</label>
                            <select id="id_session" name="id_session" class="form-select mt-1 block w-full" required>
                                <option value="" disabled selected>Choisissez une session</option>
                                @foreach ($sessions as $id => $type)
                                    <option value="{{ $id }}">{{ $type }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="code_etape" class="block text-sm font-medium text-gray-700">Filière</label>
                            <select id="code_etape" name="code_etape" class="form-select mt-1 block w-full" required>
                                <option value="" disabled selected>Choisissez une filière</option>
                                @foreach ($filieres as $id => $version_etape)
                                    <option value="{{ $id }}">{{ $version_etape }}</option> <!-- Use $id instead of $code_etape -->
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a id="downloadLink" href="#" class="bg-blue-500 text-white px-4 py-2 rounded">
                            Download PDF
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('downloadForm').addEventListener('change', function() {
    const sessionId = document.getElementById('id_session').value;
    const code_etape = document.getElementById('code_etape').value;
    const downloadLink = document.getElementById('downloadLink');

    if (sessionId && code_etape) {
        downloadLink.href = `{{ url('etudiants') }}/${sessionId}/${code_etape}/download-pdf`;
    } else {
        downloadLink.href = '#';
    }
});
    </script>
</x-app-layout>
