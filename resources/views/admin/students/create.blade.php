{{--
    Archivo: resources/views/admin/students/create.blade.php
    Roles: admin
    Rutas necesarias: Route::get('admin/students/create', [StudentController::class, 'create'])
--}}

@extends('layouts.app')

@section('title', 'Crear Estudiante - Route Tracker')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Estudiante</h1>
                <p class="text-gray-600">Registrar un nuevo estudiante en el sistema</p>
            </div>
            <a href="{{ route('admin.students') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white shadow-md rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Información del Estudiante</h3>
        </div>
        <form action="{{ route('admin.students.store') }}" method="POST" class="px-6 py-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Información Personal -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-900 border-b border-gray-200 pb-2">Información Personal</h4>

                    <div>
                        <label for="given_name" class="block text-sm font-medium text-gray-700">Nombre *</label>
                        <input type="text" name="given_name" id="given_name" value="{{ old('given_name') }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('given_name') border-red-300 @enderror">
                        @error('given_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="family_name" class="block text-sm font-medium text-gray-700">Apellido *</label>
                        <input type="text" name="family_name" id="family_name" value="{{ old('family_name') }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('family_name') border-red-300 @enderror">
                        @error('family_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="identity_number" class="block text-sm font-medium text-gray-700">Cédula de Identidad</label>
                        <input type="text" name="identity_number" id="identity_number" value="{{ old('identity_number') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('identity_number') border-red-300 @enderror">
                        @error('identity_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento *</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('birth_date') border-red-300 @enderror">
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('phone_number') border-red-300 @enderror">
                        @error('phone_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Información Académica y Familiar -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-900 border-b border-gray-200 pb-2">Información Académica y Familiar</h4>

                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700">Padre/Madre *</label>
                        <select name="parent_id" id="parent_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('parent_id') border-red-300 @enderror">
                            <option value="">Seleccionar padre/madre</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->parent_id }}" {{ old('parent_id') == $parent->parent_id ? 'selected' : '' }}>
                                    {{ $parent->account->full_name }} ({{ $parent->account->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="school_id" class="block text-sm font-medium text-gray-700">Escuela *</label>
                        <select name="school_id" id="school_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('school_id') border-red-300 @enderror">
                            <option value="">Seleccionar escuela</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->school_id }}" {{ old('school_id') == $school->school_id ? 'selected' : '' }}>
                                    {{ $school->legal_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="grade" class="block text-sm font-medium text-gray-700">Grado *</label>
                        <select name="grade" id="grade" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('grade') border-red-300 @enderror">
                            <option value="">Seleccionar grado</option>
                            <option value="1" {{ old('grade') == '1' ? 'selected' : '' }}>1° Grado</option>
                            <option value="2" {{ old('grade') == '2' ? 'selected' : '' }}>2° Grado</option>
                            <option value="3" {{ old('grade') == '3' ? 'selected' : '' }}>3° Grado</option>
                            <option value="4" {{ old('grade') == '4' ? 'selected' : '' }}>4° Grado</option>
                            <option value="5" {{ old('grade') == '5' ? 'selected' : '' }}>5° Grado</option>
                            <option value="6" {{ old('grade') == '6' ? 'selected' : '' }}>6° Grado</option>
                        </select>
                        @error('grade')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="shift" class="block text-sm font-medium text-gray-700">Turno</label>
                        <select name="shift" id="shift"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('shift') border-red-300 @enderror">
                            <option value="">Seleccionar turno</option>
                            <option value="morning" {{ old('shift') == 'morning' ? 'selected' : '' }}>Mañana</option>
                            <option value="afternoon" {{ old('shift') == 'afternoon' ? 'selected' : '' }}>Tarde</option>
                            <option value="evening" {{ old('shift') == 'evening' ? 'selected' : '' }}>Noche</option>
                        </select>
                        @error('shift')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Estado *</label>
                        <select name="status" id="status" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('status') border-red-300 @enderror">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            <option value="graduated" {{ old('status') == 'graduated' ? 'selected' : '' }}>Graduado</option>
                            <option value="transferred" {{ old('status') == 'transferred' ? 'selected' : '' }}>Transferido</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="mt-6">
                <h4 class="text-md font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">Información Adicional</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                        <textarea name="address" id="address" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('address') border-red-300 @enderror">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <div class="flex items-center">
                            <input type="checkbox" name="has_transport" id="has_transport" value="1" {{ old('has_transport') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="has_transport" class="ml-2 block text-sm text-gray-900">
                                Tiene transporte escolar asignado
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.students') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Crear Estudiante
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
