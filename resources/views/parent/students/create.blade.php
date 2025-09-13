{{--
    Archivo: resources/views/parent/students/create.blade.php
    Roles: parent
    Rutas necesarias: Route::resource('parent.students', ParentStudentController::class)
--}}

@extends('layouts.app')

@section('title', 'Registrar Nuevo Hijo')

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
                    <a href="{{ route('students.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                        Mis Hijos
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">Registrar Nuevo Hijo</span>
                </div>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Registrar Nuevo Hijo</h1>
            <p class="mt-2 text-sm text-gray-600">Completa la información de tu hijo para registrarlo en el sistema</p>
        </div>

        <!-- Formulario -->
        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('students.store') }}" class="p-6 space-y-6">
                @csrf

                <!-- Información personal -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información Personal</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <x-form-input
                            name="given_name"
                            label="Nombre(s)"
                            required
                            placeholder="Juan Carlos"
                        />

                        <x-form-input
                            name="family_name"
                            label="Apellidos"
                            required
                            placeholder="Pérez García"
                        />

                        <x-form-input
                            name="identity_number"
                            label="Número de Identificación"
                            required
                            placeholder="1234567890"
                        />

                        <x-form-input
                            name="birth_date"
                            label="Fecha de Nacimiento"
                            type="date"
                            required
                        />
                    </div>
                </div>

                <!-- Información escolar -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información Escolar</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="school_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Escuela
                            </label>
                            <select
                                name="school_id"
                                id="school_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('school_id') border-red-300 @enderror"
                            >
                                <option value="">Selecciona una escuela</option>
                                @foreach($schools ?? [] as $school)
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
                            <label for="grade" class="block text-sm font-medium text-gray-700 mb-2">
                                Grado
                            </label>
                            <select
                                name="grade"
                                id="grade"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('grade') border-red-300 @enderror"
                            >
                                <option value="">Selecciona un grado</option>
                                <option value="preescolar" {{ old('grade') == 'preescolar' ? 'selected' : '' }}>Preescolar</option>
                                <option value="primero" {{ old('grade') == 'primero' ? 'selected' : '' }}>Primero</option>
                                <option value="segundo" {{ old('grade') == 'segundo' ? 'selected' : '' }}>Segundo</option>
                                <option value="tercero" {{ old('grade') == 'tercero' ? 'selected' : '' }}>Tercero</option>
                                <option value="cuarto" {{ old('grade') == 'cuarto' ? 'selected' : '' }}>Cuarto</option>
                                <option value="quinto" {{ old('grade') == 'quinto' ? 'selected' : '' }}>Quinto</option>
                                <option value="sexto" {{ old('grade') == 'sexto' ? 'selected' : '' }}>Sexto</option>
                                <option value="septimo" {{ old('grade') == 'septimo' ? 'selected' : '' }}>Séptimo</option>
                                <option value="octavo" {{ old('grade') == 'octavo' ? 'selected' : '' }}>Octavo</option>
                                <option value="noveno" {{ old('grade') == 'noveno' ? 'selected' : '' }}>Noveno</option>
                                <option value="decimo" {{ old('grade') == 'decimo' ? 'selected' : '' }}>Décimo</option>
                                <option value="undecimo" {{ old('grade') == 'undecimo' ? 'selected' : '' }}>Undécimo</option>
                            </select>
                            @error('grade')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="shift" class="block text-sm font-medium text-gray-700 mb-2">
                                Jornada
                            </label>
                            <select
                                name="shift"
                                id="shift"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('shift') border-red-300 @enderror"
                            >
                                <option value="mixed" {{ old('shift') == 'mixed' ? 'selected' : '' }}>Mixta</option>
                                <option value="morning" {{ old('shift') == 'morning' ? 'selected' : '' }}>Mañana</option>
                                <option value="afternoon" {{ old('shift') == 'afternoon' ? 'selected' : '' }}>Tarde</option>
                            </select>
                            @error('shift')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Información de contacto -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información de Contacto</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <x-form-input
                            name="phone_number"
                            label="Teléfono"
                            type="tel"
                            placeholder="+573001234567"
                        />

                        <x-form-input
                            name="address"
                            label="Dirección"
                            placeholder="Calle 123 #45-67, Ciudad"
                        />
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>

                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save mr-2"></i>
                        Registrar Hijo
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
