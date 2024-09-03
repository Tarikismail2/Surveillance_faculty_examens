<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-2xl text-black-600 leading-tight">
                {{ __('Modules de la Filière : ' . $filiere->version_etape) }}
            </h2>
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
                                        Actions
                                    </th>
                                @else
                                    <th
                                        class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                        Nom Module
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
                                            class="px-6 py-4 border-b border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $module->lib_elp }}
                                        </td>
                                        <td
                                            class="px-6 py-4 border-b border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300">
                                            <a href="{{ route('modules.show', $module->id) }}"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out">
                                                Afficher Étudiants
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @foreach ($modulesGrouped as $module)
                                    <tr>
                                        <td
                                            class="px-6 py-4 border-b border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $module['lib_elp'] }}
                                        </td>
                                        <td
                                            class="px-6 py-4 border-b border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $module['inscriptions_count'] }}
                                        </td>
                                        <td
                                            class="px-6 py-4 border-b border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300">
                                            <a href="{{ route('modules.show', $module['lib_elp']) }}"
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
