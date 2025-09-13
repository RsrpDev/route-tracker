@extends('layouts.app')

@section('title', 'Gestión de Estudiantes')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestión de Estudiantes</h1>
                    <p class="mt-2 text-gray-600">Administra los estudiantes de tu institución</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('school.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-graduate text-2xl text-blue-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Estudiantes</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $students->total() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clipboard-list text-2xl text-green-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Inscripciones Activas</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $students->where('status', 'active')->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-route text-2xl text-purple-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Con Transporte</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $students->where('has_transport', true)->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-graduation-cap text-2xl text-yellow-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Grados Únicos</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $students->pluck('grade')->unique()->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Filtros</h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('school.students') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="grade" class="block text-sm font-medium text-gray-700">Grado</label>
                        <select name="grade" id="grade" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos los grados</option>
                            @foreach($students->pluck('grade')->unique()->sort() as $grade)
                                <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>{{ $grade }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos los estados</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Graduado</option>
                        </select>
                    </div>

                    <div>
                        <label for="transport" class="block text-sm font-medium text-gray-700">Transporte</label>
                        <select name="transport" id="transport" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos</option>
                            <option value="1" {{ request('transport') == '1' ? 'selected' : '' }}>Con Transporte</option>
                            <option value="0" {{ request('transport') == '0' ? 'selected' : '' }}>Sin Transporte</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-search mr-2"></i>
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Students Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Lista de Estudiantes</h3>
            </div>

            @if($students->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($students as $student)
                        <li>
                            <a href="{{ route('school.students.show', $student->student_id) }}" class="block hover:bg-gray-50 px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-user text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="flex items-center">
                                                <p class="text-sm font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</p>
                                                @if($student->status === 'active')
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Activo
                                                    </span>
                                                @elseif($student->status === 'inactive')
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Inactivo
                                                    </span>
                                                @else
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        Graduado
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                                <i class="fas fa-graduation-cap mr-1"></i>
                                                <span>{{ $student->grade }}</span>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-calendar mr-1"></i>
                                                <span>{{ $student->birth_date ? $student->birth_date->format('d/m/Y') : 'N/A' }}</span>
                                                @if($student->has_transport)
                                                    <span class="mx-2">•</span>
                                                    <i class="fas fa-bus text-green-600"></i>
                                                    <span class="text-green-600">Con Transporte</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $students->appends(request()->query())->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-user-graduate text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay estudiantes registrados</h3>
                    <p class="text-gray-500">No se encontraron estudiantes que coincidan con los filtros aplicados.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
