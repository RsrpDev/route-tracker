@props(['title' => '', 'subtitle' => '', 'class' => ''])

<div class="bg-white overflow-hidden shadow rounded-lg {{ $class }}">
    @if($title || $subtitle)
        <div class="px-4 py-5 sm:p-6">
            @if($title)
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $title }}</h3>
            @endif
            @if($subtitle)
                <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
            @endif
        </div>
    @endif

    <div class="px-4 py-5 sm:p-6">
        {{ $slot }}
    </div>
</div>
