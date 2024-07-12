<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @lang('Détails de l\'examen')
            </h2>
            <a href="{{ route('examens.index') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Retour à la liste des examens') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

                <h3 class="text-2xl font-bold mb-4">{{ $examen->name }}</h3>

                <!-- Détails de l'examen -->
                <div class="mb-6">
                    <h4 class="text-xl font-semibold">@lang('Informations de l\'examen')</h4>
                    <p><strong>@lang('Nom de l\'examen'):</strong> {{ $examen->id }}</p>
                    <p><strong>@lang('Date'):</strong> {{ $examen->date }}</p>
                    <p><strong>@lang('Heure de début'):</strong> {{ $examen->heure_debut }}</p>
                    <p><strong>@lang('Heure de fin'):</strong> {{ $examen->heure_fin }}</p>
                    <p><strong>@lang('Filière'):</strong> {{ $examen->module->code_etape }}</p>
                    <p><strong>@lang('Module'):</strong> {{ $examen->module->lib_elp }}</p>
                </div>

                <!-- Affectations des surveillants -->
                <h4 class="text-xl font-semibold mb-4">@lang('Affectations des surveillants')</h4>
                <table class="table-auto w-full mb-6">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">@lang('Salle')</th>
                            <th class="px-4 py-2">@lang('Surveillants')</th>
                            <th class="px-4 py-2">@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sallesAffectees as $salle)
                            <tr>
                                <td class="border px-4 py-2">{{ $salle->name }}</td>
                                <td class="border px-4 py-2">
                                    @foreach ($salle->enseignants as $enseignant)
                                        <span>{{ $enseignant->name }}</span><br>
                                    @endforeach
                                </td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('examens.editInvigilators', ['examen' => $examen->id]) }}"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        @lang('Modifier')
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
