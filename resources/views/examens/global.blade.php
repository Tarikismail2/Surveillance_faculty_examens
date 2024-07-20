<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('Global Exam Schedule')
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- Session Selector -->
                <div class="mb-4">
                    <form method="GET" action="{{ route('examens.global') }}">
                        @csrf <!-- Add CSRF token for security -->
                        <label for="session" class="block text-sm font-medium text-gray-700">@lang('Session')</label>
                        <select id="session" name="id_session"
                            class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            onchange="this.form.submit()">
                            <option value="">@lang('Select a session')</option>
                            @foreach ($sessions as $session)
                                <option value="{{ $session->id }}"
                                    {{ $selectedSessionId == $session->id ? 'selected' : '' }}>
                                    {{ $session->type }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <!-- Global Exam Schedule Table -->
                <div class="overflow-x-auto">
                    @if (isset($exams) && count($exams) > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('Date')</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('Start Time')</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('End Time')</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('Field')</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('Module')</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('Rooms')</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('Invigilators')</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('Teacher')</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        @lang('Session')</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($exams as $examen)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $examen->date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $examen->heure_debut }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $examen->heure_fin }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($examen->module)
                                                {{ $examen->module->version_etape }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $examen->module->lib_elp }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($examen->additionalSalles && count($examen->additionalSalles) > 0)
                                                @foreach ($examen->additionalSalles as $additionalSalle)
                                                    {{ $additionalSalle->name }},
                                                @endforeach
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($examen->enseignants && count($examen->enseignants) > 0)
                                                @foreach ($examen->enseignants as $enseignant)
                                                    {{ $enseignant->name }},
                                                @endforeach
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $examen->enseignant ? $examen->enseignant->name : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $examen->session ? $examen->session->type : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            <a href="{{ route('examens.global.pdf', ['id_session' => $selectedSessionId]) }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Download PDF Planification
                            </a>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('examens_global.pdf', ['id_session' => $selectedSessionId]) }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Download PDF Surveillants
                            </a>
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-4">@lang('No exams scheduled for this session.')</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>





