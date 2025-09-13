@props(['action', 'method' => 'POST', 'confirmText' => '¿Estás seguro?', 'buttonText' => 'Eliminar', 'buttonClass' => 'bg-red-600 hover:bg-red-700'])

<form method="{{ $method }}" action="{{ $action }}" class="inline">
    @csrf
    @if($method !== 'GET')
        @method($method)
    @endif

    <button
        type="submit"
        onclick="return confirm('{{ $confirmText }}')"
        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white {{ $buttonClass }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
    >
        {{ $buttonText }}
    </button>
</form>
