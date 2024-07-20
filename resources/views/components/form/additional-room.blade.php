<div class="flex mb-4 additional-room">
    <div class="w-3/4 pr-2">
        <select name="additional_salles[]" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm additional-salle-select">
            <option value="">@lang('Sélectionnez une salle')</option>
            @foreach ($salles as $salle)
                @if ($salle->id != $primaryRoomId)
                    <option value="{{ $salle->id }}" {{ $selected == $salle->id ? 'selected' : '' }}>{{ $salle->nom }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="w-1/4 pl-2">
        <button type="button" class="remove-room-button bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2">@lang('Supprimer')</button>
    </div>
</div>
