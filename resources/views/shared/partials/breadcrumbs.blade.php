{{--
    Archivo: resources/views/shared/partials/breadcrumbs.blade.php
    Componente para mostrar navegación de migas de pan
--}}

@if(isset($breadcrumbs) && count($breadcrumbs) > 0)
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            @foreach($breadcrumbs as $index => $breadcrumb)
                @if($index === 0)
                    <li class="inline-flex items-center">
                        <a href="{{ $breadcrumb['url'] }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-home mr-2"></i>
                            {{ $breadcrumb['label'] }}
                        </a>
                    </li>
                @else
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            @if(isset($breadcrumb['url']) && $index < count($breadcrumbs) - 1)
                                <a href="{{ $breadcrumb['url'] }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                    {{ $breadcrumb['label'] }}
                                </a>
                            @else
                                <span class="text-sm font-medium text-gray-500">{{ $breadcrumb['label'] }}</span>
                            @endif
                        </div>
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
