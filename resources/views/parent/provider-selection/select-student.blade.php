@extends('layouts.app')

@section('title', 'Seleccionar Estudiante')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Seleccionar Estudiante</h1>
                    <p class="mt-2 text-gray-600">Elige el estudiante para el cual deseas contratar transporte</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('parent.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Lista de Estudiantes -->
        @if($students->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($students as $student)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $student->given_name }} {{ $student->family_name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $student->school->legal_name ?? 'Sin escuela asignada' }}</p>
                                </div>
                            </div>

                            <!-- Información del estudiante -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-id-card w-4 h-4 mr-2"></i>
                                    <span>{{ $student->id_number }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-graduation-cap w-4 h-4 mr-2"></i>
                                    <span>Grado {{ $student->grade_level ?? 'No especificado' }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar w-4 h-4 mr-2"></i>
                                    <span>{{ $student->birth_date ? $student->birth_date->format('d/m/Y') : 'Fecha no especificada' }}</span>
                                </div>
                            </div>

                            <!-- Estado del contrato -->
                            <div class="mb-4">
                                @if($student->transportContract)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Con contrato activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Sin contrato
                                    </span>
                                @endif
                            </div>

                            <!-- Botón de acción -->
                            <div class="flex space-x-2">
                                <a href="{{ route('parent.provider-selection.index', ['student_id' => $student->student_id]) }}"
                                   class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-search mr-1"></i>
                                    {{ $student->transportContract ? 'Ver Contrato' : 'Buscar Transporte' }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes estudiantes registrados</h3>
                <p class="text-gray-500 mb-6">
                    Necesitas registrar al menos un estudiante para poder contratar servicios de transporte.
                </p>
                <a href="{{ route('parent.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-arrow-left mr-2"></i>Volver al Dashboard
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

