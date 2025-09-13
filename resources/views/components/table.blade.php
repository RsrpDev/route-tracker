@props(['headers' => [], 'rows' => [], 'emptyMessage' => 'No hay datos disponibles'])

<div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
    <table class="min-w-full divide-y divide-gray-300">
        <thead class="bg-gray-50">
            <tr>
                @foreach($headers as $header)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $header }}
                    </th>
                @endforeach
                <th scope="col" class="relative px-6 py-3">
                    <span class="sr-only">Acciones</span>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($rows as $row)
                <tr class="hover:bg-gray-50">
                    @foreach($row as $cell)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $cell }}
                        </td>
                    @endforeach
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        {{ $slot }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) + 1 }}" class="px-6 py-4 text-center text-sm text-gray-500">
                        {{ $emptyMessage }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
