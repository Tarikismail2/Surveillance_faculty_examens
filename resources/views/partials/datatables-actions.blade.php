<div class="flex space-x-2">
    <a href="{{ $editUrl }}" class="text-yellow-600 hover:text-yellow-900">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ $deleteUrl }}" method="POST" class="inline-block" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ $confirmMessage }}')">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>
