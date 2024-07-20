@props(['name', 'label', 'options', 'selected' => '', 'required' => false])

<div class="form-group mb-4">
    <label for="{{ $name }}" class="block text-gray-700 dark:text-gray-300">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" {{ $required ? 'required' : '' }}>
        @foreach ($options as $option)
            <option value="{{ $option['value'] }}" {{ $selected == $option['value'] ? 'selected' : '' }}>{{ $option['label'] }}</option>
        @endforeach
    </select>
    @error($name)
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
