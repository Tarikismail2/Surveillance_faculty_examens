<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-2xl text-black-600 leading-tight">
                {{ __('Modules de la Filière : ' . $filiere->version_etape) }}
            </h2>
            @if( $filiere->type =='old')
            <a href="{{ route('modules.create', ['code_etape' => $filiere->code_etape, 'session_id' => $filiere->id_session]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 00-2 0v3H6a1 1 0 000 2h3v3a1 1 0 002 0v-3h3a1 1 0 000-2h-3V7z" clip-rule="evenodd" />
                </svg>
                <span class="hidden md:inline">Créer une Nouveau module </span>
            </a>
            @endif
        </div>
       
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-800 shadow-md rounded-lg">
                        <thead>
                            <tr>
                            @if ($filiere->type == 'old')
                                    <th
                                        class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                        Code Module
                                    </th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                        Nom Module
                                    </th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                        Nombre des inscrits
                                    </th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                        Actions
                                    </th>

                                    @else
                                    
                                    <th
                                        class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                        Nom Module
                                    </th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                        Nombre des inscrits
                                    </th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                        Actions
                                    </th>
                                    @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if ($filiere->type == 'old')
                                @foreach ($modules as $module)
                                    <tr>
                                        <td
                                            class="px-6 py-4 border-b border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $module->code_elp }}
                                        </td>
                                        <td
                                            class="px-6 py-4 border-b border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300 font-bold">
                                            {{ $module->lib_elp }}
                                        </td>
                                        <td
                                            class="px-6 py-4 border-b border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300 text-center font-bold tracking-widest">
                                            {{ $module->total_inscriptions }}
                                        </td>
                                        <td
                                            class="px-6 py-4 border-b border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300">
                                            <a href="" class="text-yellow-600 hover:text-yellow-700 ml-4" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L5 12.172V15h2.828l9.586-9.586a2 2 0 000-2.828zM4 13H3v4a1 1 0 001 1h4v-1H4v-3z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('modules.destroy', [ 'code_etape' => $filiere->code_etape, 'code_elp' => $module->code_elp ]) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer ce module ?') }}');">
    @csrf
    @method('DELETE')
    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-600" title="{{ __('Supprimer') }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M6 8a1 1 0 011-1h6a1 1 0 011 1v9a1 1 0 11-2 0v-1H8v1a1 1 0 11-2 0V8zm3-3a1 1 0 00-1-1V3a1 1 0 112 0v1a1 1 0 00-1 1z" clip-rule="evenodd" />
        </svg>
    </button>
    <input type="hidden" name="id_session" value="{{ $id_session }}">
</form>

                                        <a href="{{ route('modules.show', [$module->code_elp, $filiere->code_etape]) }}"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out">
                                            <i class="fa fa-user-graduate"></i> <!-- Font Awesome student icon -->
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @foreach ($modules as $module)
                                    <tr>
                                 
                                        <td
                                            class="px-6 py-4 border-b border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300 font-bold">
                                            {{ $module['lib_elp'] }}
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300 text-center font-bold">
    {{ $module['number_of_inscriptions'] }}
</td>

                                        <td
                                            class="px-6 py-4 border-b border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300">
                                            <a href="{{ route('modules.show', [$module->lib_elp, $filiere->code_etape]) }}"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out">
                                                Afficher Étudiants
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
