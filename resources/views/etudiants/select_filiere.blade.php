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
                                @foreach ($sessions as $session)
                                    <option value="{{ $session->id }}">
                                        {{ $session->type }}
                                        ({{ \Carbon\Carbon::parse($session->date_debut)->format('d/m/Y') }} -
                                        {{ \Carbon\Carbon::parse($session->date_fin)->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filière -->
                        <div>
                            <label for="filiere" class="block text-sm font-medium text-gray-700">Filière</label>
                            <select id="filiere" name="code_etape" class="form-select mt-1 block w-full" required>
                                <option value="" disabled selected>Sélectionnez une filière</option>

                                <!-- New Filière -->
                                <optgroup label="Nouveaux Filières">
                                    @foreach ($filieres as $filiere)
                                        @if ($filiere->type === 'new')
                                            <option value="{{ $filiere->code_etape }}">
                                                {{ $filiere->version_etape }}
                                            </option>
                                        @endif
                                    @endforeach
                                </optgroup>

                                <!-- Normal Filière -->
                                <optgroup label="Filières Normales">
                                    @foreach ($filieres as $filiere)
                                        @if ($filiere->type === 'old')
                                            <option value="{{ $filiere->code_etape }}">
                                                {{ $filiere->version_etape }}
                                            </option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            </select>
                            @error('code_etape')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <a id="downloadLink" href="#" class="bg-blue-500 text-white px-4 py-2 rounded">
                            Télécharger PDF
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#id_session').select2({
                placeholder: "Choisir une session",
                allowClear: true
            }).on('change', updateDownloadLink);

            $('#filiere').select2({
                placeholder: "Choisir une filière",
                allowClear: true
            }).on('change', updateDownloadLink);

            function updateDownloadLink() {
                const sessionId = $('#id_session').val();
                const codeEtape = $('#filiere').val();
                const downloadLink = $('#downloadLink');

                if (sessionId && codeEtape) {
                    downloadLink.attr('href', `{{ url('etudiants') }}/${sessionId}/${codeEtape}/download-pdf`);
                } else {
                    downloadLink.attr('href', '#');
                }
            }
        });
    </script>
</x-app-layout>
