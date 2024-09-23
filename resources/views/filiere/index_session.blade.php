<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-2xl text-black-600 leading-tight">
                {{ __('Liste des Filières') }}
            </h2>
            <a href="{{ route('filiere.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 00-2 0v3H6a1 1 0 000 2h3v3a1 1 0 002 0v-3h3a1 1 0 000-2h-3V7z" clip-rule="evenodd" />
                </svg>
                <span class="hidden md:inline">Créer une Nouvelle Filière</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">{{ session('success') }}</strong>
                    </div>
                @endif

                <div class="mb-4">
    <label for="session" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        @lang('Session')
    </label>
    <select id="sessionn" name="id_session"
        class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        
        <!-- Placeholder option -->
        <option value="" disabled selected>@lang('Choisir une session')</option>

        @foreach ($sessions as $session)
            <option value="{{ $session->id }}">
                {{ $session->type }} ({{ $session->date_debut }} - {{ $session->date_fin }})
            </option>
        @endforeach
    </select>

            </div>
        </div>
    </div>

  
    <script>
document.getElementById('sessionn').addEventListener('change', function() {
    const id_session = this.value;
    if (id_session) {
        // Use the correct route structure
        const url = "{{ route('filiere.index', ':id_session') }}";
        window.location.href = url.replace(':id_session', id_session);
    }
});

</script>
</x-app-layout>