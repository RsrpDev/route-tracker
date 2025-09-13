{{--
    Archivo: resources/views/parent/students/index.blade.php
    Roles: parent
    Rutas necesarias: Route::resource('parent.students', ParentStudentController::class)
--}}

@extends('layouts.app')

@section('title', 'Mis Hijos')

@section('breadcrumbs')
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home mr-2"></i>
                    Inicio
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('parent.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                        Dashboard de Padre
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">Mis Hijos</span>
                </div>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <!-- Encabezado -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Mis Hijos</h1>
                <p class="mt-2 text-sm text-gray-600">Gestiona la información de tus hijos registrados</p>
            </div>
            <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i>
                Agregar Hijo
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" action="{{ route('students.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="q" class="block text-sm font-medium text-gray-700">Buscar</label>
                    <input
                        type="text"
                        name="q"
                        id="q"
                        value="{{ request('q') }}"
                        placeholder="Nombre, identificación..."
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    >
                </div>

                <div>
                    <label for="grade" class="block text-sm font-medium text-gray-700">Grado</label>
                    <select
                        name="grade"
                        id="grade"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    >
                        <option value="">Todos los grados</option>
                        <option value="preescolar" {{ request('grade') == 'preescolar' ? 'selected' : '' }}>Preescolar</option>
                        <option value="primero" {{ request('grade') == 'primero' ? 'selected' : '' }}>Primero</option>
                        <option value="segundo" {{ request('grade') == 'segundo' ? 'selected' : '' }}>Segundo</option>
                        <option value="tercero" {{ request('grade') == 'tercero' ? 'selected' : '' }}>Tercero</option>
                        <option value="cuarto" {{ request('grade') == 'cuarto' ? 'selected' : '' }}>Cuarto</option>
                        <option value="quinto" {{ request('grade') == 'quinto' ? 'selected' : '' }}>Quinto</option>
                        <option value="sexto" {{ request('grade') == 'sexto' ? 'selected' : '' }}>Sexto</option>
                        <option value="septimo" {{ request('grade') == 'septimo' ? 'selected' : '' }}>Séptimo</option>
                        <option value="octavo" {{ request('grade') == 'octavo' ? 'selected' : '' }}>Octavo</option>
                        <option value="noveno" {{ request('grade') == 'noveno' ? 'selected' : '' }}>Noveno</option>
                        <option value="decimo" {{ request('grade') == 'decimo' ? 'selected' : '' }}>Décimo</option>
                        <option value="undecimo" {{ request('grade') == 'undecimo' ? 'selected' : '' }}>Undécimo</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-search mr-2"></i>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de estudiantes -->
    @if($students && $students->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul role="list" class="divide-y divide-gray-200">
                @foreach($students as $student)
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-child text-indigo-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center">
                                            <p class="text-sm font-medium text-indigo-600 truncate">
                                                {{ $student->given_name }} {{ $student->family_name }}
                                            </p>
                                            @if($student->transportContracts && $student->transportContracts->where('contract_status', 'active')->count() > 0)
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Inscrito
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-1 flex items-center text-sm text-gray-500">
                                            <p>
                                                <i class="fas fa-id-card mr-1"></i>
                                                {{ $student->identity_number }}
                                            </p>
                                            @if($student->grade)
                                                <span class="mx-2">•</span>
                                                <p>{{ $student->grade }}</p>
                                            @endif
                                            @if($student->school)
                                                <span class="mx-2">•</span>
                                                <p>{{ $student->school->legal_name }}</p>
                                            @endif
                                        </div>
                                        @if($student->transportContracts && $student->transportContracts->count() > 0)
                                            <div class="mt-2">
                                                <p class="text-xs text-gray-500">
                                                    <i class="fas fa-route mr-1"></i>
                                                    Rutas: {{ $student->transportContracts->pluck('pickupRoute.route_name')->implode(', ') }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('students.show', $student->student_id) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-eye"></i>
                                        <span class="sr-only">Ver</span>
                                    </a>
                                    <a href="{{ route('students.edit', $student->student_id) }}" class="text-gray-600 hover:text-gray-900">
                                        <i class="fas fa-edit"></i>
                                        <span class="sr-only">Editar</span>
                                    </a>
                                    <form method="POST" action="{{ route('students.destroy', $student->student_id) }}" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este estudiante?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                            <span class="sr-only">Eliminar</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $students->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-child text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes hijos registrados</h3>
            <p class="text-gray-500 mb-6">Comienza registrando a tu primer hijo para gestionar su transporte escolar.</p>
            <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i>
                Registrar Primer Hijo
            </a>
        </div>
    @endif
@endsection
