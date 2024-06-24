<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter un Enseignant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('enseignants.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="name" class="block text-gray-700 dark:text-gray-300">Nom</label>
                        <input type="text" name="name" id="name" class="form-control mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div class="form-group mb-4">
                        <label for="email" class="block text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" name="email" id="email" class="form-control mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div class="form-group mb-4">
                        <label for="department_id" class="block text-gray-700 dark:text-gray-300">Département</label>
                        <select name="department_id" id="department_id" class="form-control mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Ajouter') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
