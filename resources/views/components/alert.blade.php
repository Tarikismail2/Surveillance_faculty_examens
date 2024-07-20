@props(['type', 'messages'])

@php
    $alertClasses = [
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error' => 'bg-red-100 border-red-400 text-red-700',
    ];
@endphp

<div class="mb-4 {{ $alertClasses[$type] }} border-l-4 p-4">
    @foreach ($messages as $message)
        <p>{{ $message }}</p>
    @endforeach
</div>
