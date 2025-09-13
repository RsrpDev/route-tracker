@extends('layouts.app')

@section('breadcrumbs')
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                <i class="fas fa-home mr-2"></i>
                Inicio
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="text-sm font-medium text-gray-500">Estudiantes</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">
                <i class="fas fa-user-graduate mr-2"></i>
                Gestión de Estudiantes
            </h2>
            @can('create', App\Models\Student::class)
            <a href="{{ route('students.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Nuevo Estudiante
            </a>
            @endcan
        </div>
    </div>

    <div class="p-6">
        <!-- Filtros -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="q" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input type="text" name="q" id="q" value="{{ request('q') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Nombre, identificación...">
                </div>
                <div>
                    <label for="grade" class="block text-sm font-medium text-gray-700 mb-1">Grado</label>
                    <select name="grade" id="grade"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos los grados</option>
                        @foreach($students->pluck('grade')->unique()->filter() as $grade)
                            <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>
                                {{ $grade }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="shift" class="block text-sm font-medium text-gray-700 mb-1">Turno</label>
                    <select name="shift" id="shift"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos los turnos</option>
                        <option value="morning" {{ request('shift') == 'morning' ? 'selected' : '' }}>Mañana</option>
                        <option value="afternoon" {{ request('shift') == 'afternoon' ? 'selected' : '' }}>Tarde</option>
                        <option value="mixed" {{ request('shift') == 'mixed' ? 'selected' : '' }}>Mixto</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-search mr-2"></i>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de estudiantes -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estudiante
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Identificación
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Padre/Madre
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Escuela
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Grado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Turno
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rutas Activas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($students as $student)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-blue-600">
                                            {{ substr($student->given_name, 0, 1) }}{{ substr($student->family_name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $student->given_name }} {{ $student->family_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->age . ' años' : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $student->identity_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $student->parentProfile->account->full_name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $student->school->legal_name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $student->grade ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($student->shift)
                                @case('morning')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-sun mr-1"></i>
                                        Mañana
                                    </span>
                                    @break
                                @case('afternoon')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <i class="fas fa-cloud-sun mr-1"></i>
                                        Tarde
                                    </span>
                                    @break
                                @case('mixed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Mixto
                                    </span>
                                    @break
                                @default
                                    <span class="text-sm text-gray-500">N/A</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $student->transportContracts->where('contract_status', 'active')->count() }} contrato(s)
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('students.show', $student) }}"
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('update', $student)
                                <a href="{{ route('students.edit', $student) }}"
                                   class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center py-8">
                                <i class="fas fa-user-graduate text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium text-gray-400">No se encontraron estudiantes</p>
                                <p class="text-sm text-gray-300">Registra el primer estudiante para comenzar</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($students->hasPages())
        <div class="mt-6">
            {{ $students->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
