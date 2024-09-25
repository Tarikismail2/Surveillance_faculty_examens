<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-blue-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-blue-800 leading-tight">
                @lang('Global Exam Schedule')
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- Session Selector -->
                <div class="mb-4">
                    <form method="GET" action="{{ route('examens.global') }}">
                        @csrf
                        <label for="session"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Session')</label>
                        <select id="session" name="id_session"
                            class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            onchange="this.form.submit()">
                            <option value="">@lang('Choisir une session')</option>
                            @foreach ($sessions as $session)
                                <option value="{{ $session->id }}"
                                    {{ $selectedSessionId == $session->id ? 'selected' : '' }}>
                                    {{ $session->type }} ({{ $session->date_debut }} - {{ $session->date_fin }})
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <!-- Download Buttons -->
                <div class="mt-6 flex gap-4">
                    <!-- Vérifiez que $selectedSessionId est correctement défini -->
                    @if (isset($selectedSessionId))
                        <!-- Download Buttons -->
                        <div class="mt-6 flex gap-4">
                            <a href="{{ route('examens.global.pdf', ['sessionId' => $selectedSessionId]) }}"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out flex items-center space-x-2">
                                <i class="fas fa-download"></i>
                                @lang('Télécharger l\'emploi Globale sous forme PDF')
                            </a>
                        </div>
                    @else
                        <p class="text-center text-red-500">@lang('Please select a session to download the PDF')</p>
                    @endif

                </div>

                <!-- Global Exam Schedule Table -->
                <div class="overflow-x-auto mt-6">
                    @if (isset($exams) && count($exams) > 0)
                        <table id="exam-schedule" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-800">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('Date')</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('Heure Début')</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('Heure Fin')</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('Filière')</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('Module')</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('Locaux')</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('Surveillants')</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('Responsable du module')</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('Session')</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($exams as $examen)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $examen->date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $examen->heure_debut }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $examen->heure_fin }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $firstModule = $examen->modules->first();
                                            @endphp
                                            @if ($firstModule)
                                                {{ $firstModule->filiere->version_etape ?? 'N/A' }}
                                                @if ($firstModule->filiere->type == 'new')
                                                    {{-- Do something if it's a new filière --}}
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($firstModule)
                                                {{ $firstModule->lib_elp }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($examen->sallesSupplementaires && count($examen->Salles) > 0)
                                                @foreach ($examen->sallesSupplementaires as $sallesSupplementaire)
                                                    {{ $sallesSupplementaire->name }},
                                                @endforeach
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if (!empty($examen->enseignants))
                                                @foreach ($examen->enseignants as $enseignant)
                                                    {{ $enseignant->name }},
                                                @endforeach
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $examen->enseignant ? $examen->enseignant->name : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $examen->session->type }} ({{ $examen->session->date_debut }} -
                                            {{ $examen->session->date_fin }})
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-center text-gray-500 py-4">@lang('No exams scheduled for this session.')</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Include DataTables CSS and JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#exam-schedule').DataTable({
                "paging": true,
                "searching": true,
                "info": false,
                "ordering": true,
                "language": {
                    "search": "@lang('Search')",
                    "lengthMenu": "@lang('Show _MENU_ entries')",
                    "zeroRecords": "@lang('No matching records found')",
                    "info": "@lang('Showing _START_ to _END_ of _TOTAL_ entries')",
                    "infoEmpty": "@lang('No entries available')",
                    "infoFiltered": "@lang('(filtered from _MAX_ total entries)')"
                }
            });
        });
    </script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Script pour activer Select2 -->
<script>
    $(document).ready(function() {
        $('#session').select2({
            placeholder: "@lang('Choisir une session')",
            allowClear: true 
        });
    });
</script>
</x-app-layout>
