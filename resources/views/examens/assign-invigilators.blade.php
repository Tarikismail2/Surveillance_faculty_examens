<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-blue-100 p-4 rounded-lg shadow-md">
            <h2 class="font-semibold text-xl text-blue-800 leading-tight">
                @lang('Affectation des surveillants')
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-lg sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4 text-red-600 bg-red-100 border border-red-300 rounded-lg p-3">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-4 text-green-600 bg-green-100 border border-green-300 rounded-lg p-3">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('examens.assignInvigilators', ['id' => $examen->id]) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($salles as $salle)
                            @if ($examen->salles->contains('id', $salle->id))
                                <div id="salle_{{ $salle->id }}" class="p-4 border rounded-lg bg-gray-50 dark:bg-gray-700 shadow-md">
                                    <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-100 mb-2">@lang('Salle'): {{ $salle->name }}</h3>
                                    <div class="enseignants-container mb-4" id="container_salle_{{ $salle->id }}">
                                        <div class="flex items-center mb-2">
                                            <select name="enseignants[{{ $salle->id }}][]" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <option value="">@lang('Choisir un surveillant')</option>
                                                @foreach ($enseignants as $enseignant)
                                                    <option value="{{ $enseignant->id }}">{{ $enseignant->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="ml-2 bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded" onclick="addSurveillant({{ $salle->id }})">
                                                @lang('Ajouter')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            @lang('Enregistrer')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function addSurveillant(salleId) {
        const container = document.querySelector(`#container_salle_${salleId}`);
        const div = document.createElement('div');
        div.classList.add('flex', 'items-center', 'mb-2');
        
        const select = document.createElement('select');
        select.name = `enseignants[${salleId}][]`;
        select.classList.add('mt-1', 'block', 'w-full', 'border', 'border-gray-300', 'rounded-md', 'shadow-sm', 'focus:border-blue-500', 'focus:ring', 'focus:ring-blue-200', 'focus:ring-opacity-50');
        select.innerHTML = getEnseignantOptions();
        
        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('ml-2', 'bg-red-500', 'hover:bg-red-700', 'text-white', 'font-bold', 'py-1', 'px-2', 'rounded');
        removeButton.innerHTML = '@lang("Supprimer")';
        removeButton.onclick = function() {
            removeSurveillant(removeButton);
        };
        
        div.appendChild(select);
        div.appendChild(removeButton);
        container.appendChild(div);
        
        updateEnseignantOptions();
    }

    function removeSurveillant(button) {
        button.parentNode.remove();
        updateEnseignantOptions();
    }

    function getEnseignantOptions() {
        let options = '<option value="">@lang("Choisir un surveillant")</option>';
        @foreach ($enseignants as $enseignant)
            options += `<option value="{{ $enseignant->id }}">{{ $enseignant->name }}</option>`;
        @endforeach
        return options;
    }

    function updateEnseignantOptions() {
        const selectedEnseignants = new Set();
        
        document.querySelectorAll('select[name^="enseignants"]').forEach(select => {
            if (select.value) {
                selectedEnseignants.add(select.value);
            }
        });

        document.querySelectorAll('select[name^="enseignants"]').forEach(select => {
            select.querySelectorAll('option').forEach(option => {
                if (option.value && selectedEnseignants.has(option.value) && option.value !== select.value) {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateEnseignantOptions();
    });
</script>
