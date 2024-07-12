<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('Affectation des surveillants')
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4 text-red-600">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-4 text-green-600">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('examens.assignInvigilators', $examen->id) }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($salles as $salle)
                            @if ($examen->salles->contains('id', $salle->id))
                                <div id="salle_{{ $salle->id }}">
                                    <h3 class="font-semibold text-lg">@lang('Salle'): {{ $salle->name }}</h3>
                                    <label class="block text-sm font-medium text-gray-700">@lang('Surveillants')</label>
                                    <div class="enseignants-container">
                                        <div class="flex items-center mb-2">
                                            <select name="enseignants[{{ $salle->id }}][]" class="mt-1 block w-full">
                                                @foreach ($enseignants as $enseignant)
                                                    <option value="{{ $enseignant->id }}">{{ $enseignant->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded" onclick="removeSurveillant(this)">
                                                @lang('Supprimer')
                                            </button>
                                        </div>
                                    </div>
                                    <button type="button" class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" onclick="addSurveillant({{ $salle->id }})">
                                        @lang('Ajouter un surveillant')
                                    </button>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            @lang('Enregistrer')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function addSurveillant(salleId) {
            const container = document.querySelector(`#salle_${salleId} .enseignants-container`);
            const div = document.createElement('div');
            div.classList.add('flex', 'items-center', 'mb-2');
            const select = document.createElement('select');
            select.name = `enseignants[${salleId}][]`;
            @foreach ($enseignants as $enseignant)
                const option{{ $enseignant->id }} = document.createElement('option');
                option{{ $enseignant->id }}.value = '{{ $enseignant->id }}';
                option{{ $enseignant->id }}.textContent = '{{ $enseignant->name }}';
                select.appendChild(option{{ $enseignant->id }});
            @endforeach
            div.appendChild(select);
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.classList.add('ml-2', 'bg-red-500', 'hover:bg-red-700', 'text-white', 'font-bold', 'py-1', 'px-2', 'rounded');
            removeButton.innerHTML = '@lang('Supprimer')';
            removeButton.onclick = function() {
                removeSurveillant(removeButton);
            };
            div.appendChild(removeButton);
            container.appendChild(div);
        }

        function removeSurveillant(button) {
            button.parentNode.remove();
        }
    </script>
</x-app-layout>
