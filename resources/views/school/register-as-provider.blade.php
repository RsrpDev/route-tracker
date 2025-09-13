@extends('layouts.app')

@section('title', 'Registro como Proveedor de Transporte')

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
                    <span class="text-sm font-medium text-gray-500">Registro como Proveedor</span>
                </div>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                <i class="fas fa-bus text-2xl text-blue-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Registro como Proveedor de Transporte</h1>
            <p class="mt-2 text-gray-600">Registra tu escuela como proveedor de servicios de transporte estudiantil</p>
        </div>
    </div>

    <!-- Información de la escuela -->
    <div class="bg-blue-50 rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-blue-900 mb-4">
            <i class="fas fa-school mr-2"></i>
            Información de tu Escuela
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-blue-700 font-medium">Nombre Legal</p>
                <p class="text-blue-900">{{ $school->legal_name }}</p>
            </div>
            <div>
                <p class="text-sm text-blue-700 font-medium">NIT</p>
                <p class="text-blue-900">{{ $school->nit }}</p>
            </div>
            <div>
                <p class="text-sm text-blue-700 font-medium">Dirección</p>
                <p class="text-blue-900">{{ $school->address }}</p>
            </div>
            <div>
                <p class="text-sm text-blue-700 font-medium">Teléfono</p>
                <p class="text-blue-900">{{ $school->phone_number }}</p>
            </div>
        </div>
    </div>

    <!-- Formulario de registro -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <form method="POST" action="{{ route('school.store-as-provider') }}">
            @csrf

            <div class="space-y-6">
                <!-- Información del servicio de transporte -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        Información del Servicio de Transporte
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="transport_service_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre del Servicio de Transporte *
                            </label>
                            <input type="text"
                                   id="transport_service_name"
                                   name="transport_service_name"
                                   value="{{ old('transport_service_name') }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Ej: Transporte Escolar San José"
                                   required>
                            @error('transport_service_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="transport_capacity" class="block text-sm font-medium text-gray-700 mb-2">
                                Capacidad de Pasajeros *
                            </label>
                            <input type="number"
                                   id="transport_capacity"
                                   name="transport_capacity"
                                   value="{{ old('transport_capacity') }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Ej: 30"
                                   min="1"
                                   required>
                            @error('transport_capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="transport_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción del Servicio
                        </label>
                        <textarea id="transport_description"
                                  name="transport_description"
                                  rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Describe los servicios de transporte que ofrecerás...">{{ old('transport_description') }}</textarea>
                        @error('transport_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Información de contacto -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-phone mr-2"></i>
                        Información de Contacto
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="transport_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono del Servicio *
                            </label>
                            <input type="tel"
                                   id="transport_phone"
                                   name="transport_phone"
                                   value="{{ old('transport_phone', $school->phone_number) }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Ej: +57 300 123 4567"
                                   required>
                            @error('transport_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="transport_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email del Servicio *
                            </label>
                            <input type="email"
                                   id="transport_email"
                                   name="transport_email"
                                   value="{{ old('transport_email') }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="transporte@escuela.com"
                                   required>
                            @error('transport_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="transport_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Dirección del Servicio *
                        </label>
                        <input type="text"
                               id="transport_address"
                               name="transport_address"
                               value="{{ old('transport_address', $school->address) }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Dirección donde operará el servicio de transporte"
                               required>
                        @error('transport_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                <!-- Información importante -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Información Importante
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Al registrarte como proveedor de transporte, podrás gestionar rutas y servicios de transporte estudiantil.</li>
                                    <li>Podrás acceder al dashboard de proveedor desde tu cuenta de escuela.</li>
                                    <li>Una vez registrado, podrás crear rutas y gestionar estudiantes que utilicen tu servicio.</li>
                                    <li><strong>Los conductores deben registrarse por separado</strong> con sus propias licencias y documentos.</li>
                                    <li>Podrás agregar vehículos y asignar conductores desde el dashboard de proveedor.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('dashboard') }}"
                   class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-bus mr-2"></i>
                    Registrar como Proveedor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
