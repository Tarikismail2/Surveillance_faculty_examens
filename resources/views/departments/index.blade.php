<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Départements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <a href="{{ route('departments.create') }}" class="btn btn-primary mb-4">{{ __('Ajouter un Département') }}</a>
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">{{ __('Nom') }}</th>
                            <th class="px-4 py-2">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $department)
                            <tr>
                                <td class="border px-4 py-2">{{ $department->name }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('departments', $department->id_department) }}" class="btn btn-warning">{{ __('Modifier') }}</a>
                                    <form action="{{ route('departments.destroy', $department->id_department) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">{{ __('Supprimer') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
