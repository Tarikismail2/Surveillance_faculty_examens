<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-blue-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-blue-800 leading-tight">
                @lang('Détails de l\'examen')
            </h2>
            <a href="{{ route('examens.index', ['sessionId' => $examen->id]) }}" 
               class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg flex items-center space-x-2 transition duration-200">
                <i class="fas fa-arrow-left"></i>
                <span>{{ __('Retour à la liste des examens') }}</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-lg sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm" role="alert">
                        <i class="fas fa-check-circle"></i> 
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center space-x-2">
                    <i class="fas fa-clipboard-list"></i>
                    <span>{{ $examen->name }}</span>
                </h3>

                <!-- Détails de l'examen -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm p-4 mb-6">
                    <h4 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center space-x-2">
                        <i class="fas fa-info-circle"></i>
                        <span>@lang('Informations de l\'examen')</span>
                    </h4>
                    <p class="text-gray-600 dark:text-gray-300"><strong>@lang('Nom de l\'examen'):</strong> {{ $examen->id }}</p>
                    <p class="text-gray-600 dark:text-gray-300"><strong>@lang('Date'):</strong> {{ $examen->date }}</p>
                    <p class="text-gray-600 dark:text-gray-300"><strong>@lang('Heure de début'):</strong> {{ $examen->heure_debut }}</p>
                    <p class="text-gray-600 dark:text-gray-300"><strong>@lang('Heure de fin'):</strong> {{ $examen->heure_fin }}</p>
                    <p class="text-gray-600 dark:text-gray-300"><strong>@lang('Filière'):</strong> {{ $examen->module->version_etape }}</p>
                    <p class="text-gray-600 dark:text-gray-300"><strong>@lang('Module'):</strong> {{ $examen->module->lib_elp }}</p>
                </div>

                <!-- Affectations des surveillants -->
                <h4 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center space-x-2">
                    <i class="fas fa-users"></i>
                    <span>@lang('Affectations des surveillants')</span>
                </h4>
                <div class="overflow-x-auto">
                    <table class="table-auto w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">@lang('Salle')</th>
                                <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">@lang('Surveillants')</th>
                                <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sallesAffectees as $salle)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-2">{{ $salle->name }}</td>
                                    <td class="px-4 py-2">
                                        @foreach ($salle->enseignants as $enseignant)
                                            <span>{{ $enseignant->name }}</span><br>
                                        @endforeach
                                    </td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('examens.editInvigilators', ['examen' => $examen->id]) }}" 
                                           class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg flex items-center space-x-2 transition duration-200">
                                            <i class="fas fa-edit"></i>
                                            <span>@lang('Modifier')</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
