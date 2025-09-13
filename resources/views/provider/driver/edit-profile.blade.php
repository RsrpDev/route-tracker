@extends('layouts.app')

@section('title', 'Editar Perfil - Conductor Independiente')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Editar Perfil</h2>
                <p class="text-gray-600">Actualiza tu información personal y profesional</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('driver.profile') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al Perfil
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('driver.profile.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Información Personal -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Información Personal</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="display_name" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo *</label>
                        <input type="text" id="display_name" name="display_name" value="{{ old('display_name', $provider->display_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('display_name') border-red-500 @enderror" required>
                        @error('display_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico *</label>
                        <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $provider->contact_email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('contact_email') border-red-500 @enderror" required>
                        @error('contact_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Teléfono de Contacto *</label>
                        <input type="tel" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $provider->contact_phone) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('contact_phone') border-red-500 @enderror" required>
                        @error('contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Información Profesional -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Información Profesional</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="driver_license_number" class="block text-sm font-medium text-gray-700 mb-2">Número de Licencia *</label>
                        <input type="text" id="driver_license_number" name="driver_license_number" value="{{ old('driver_license_number', $provider->driver_license_number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('driver_license_number') border-red-500 @enderror" required>
                        @error('driver_license_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="driver_license_category" class="block text-sm font-medium text-gray-700 mb-2">Categoría de Licencia *</label>
                        <select id="driver_license_category" name="driver_license_category"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('driver_license_category') border-red-500 @enderror" required>
                            <option value="">Seleccionar categoría</option>
                            <option value="A1" {{ old('driver_license_category', $provider->driver_license_category) == 'A1' ? 'selected' : '' }}>A1 - Motocicletas hasta 125cc</option>
                            <option value="A2" {{ old('driver_license_category', $provider->driver_license_category) == 'A2' ? 'selected' : '' }}>A2 - Motocicletas hasta 35kW</option>
                            <option value="A" {{ old('driver_license_category', $provider->driver_license_category) == 'A' ? 'selected' : '' }}>A - Motocicletas sin límite</option>
                            <option value="B1" {{ old('driver_license_category', $provider->driver_license_category) == 'B1' ? 'selected' : '' }}>B1 - Cuatriciclos</option>
                            <option value="B" {{ old('driver_license_category', $provider->driver_license_category) == 'B' ? 'selected' : '' }}>B - Automóviles hasta 3500kg</option>
                            <option value="C1" {{ old('driver_license_category', $provider->driver_license_category) == 'C1' ? 'selected' : '' }}>C1 - Vehículos hasta 7500kg</option>
                            <option value="C" {{ old('driver_license_category', $provider->driver_license_category) == 'C' ? 'selected' : '' }}>C - Vehículos de carga</option>
                            <option value="D1" {{ old('driver_license_category', $provider->driver_license_category) == 'D1' ? 'selected' : '' }}>D1 - Autobuses hasta 16 plazas</option>
                            <option value="D" {{ old('driver_license_category', $provider->driver_license_category) == 'D' ? 'selected' : '' }}>D - Autobuses sin límite</option>
                        </select>
                        @error('driver_license_category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="driver_license_expiration" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Vencimiento *</label>
                        <input type="date" id="driver_license_expiration" name="driver_license_expiration" value="{{ old('driver_license_expiration', $provider->driver_license_expiration ? $provider->driver_license_expiration->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('driver_license_expiration') border-red-500 @enderror" required>
                        @error('driver_license_expiration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="driver_years_experience" class="block text-sm font-medium text-gray-700 mb-2">Años de Experiencia *</label>
                        <input type="number" id="driver_years_experience" name="driver_years_experience" value="{{ old('driver_years_experience', $provider->driver_years_experience) }}"
                               min="0" max="50" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('driver_years_experience') border-red-500 @enderror" required>
                        @error('driver_years_experience')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="default_commission_rate" class="block text-sm font-medium text-gray-700 mb-2">Comisión por Defecto (%) *</label>
                        <input type="number" id="default_commission_rate" name="default_commission_rate" value="{{ old('default_commission_rate', $provider->default_commission_rate) }}"
                               min="0" max="100" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('default_commission_rate') border-red-500 @enderror" required>
                        @error('default_commission_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('provider.driver.profile') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Formulario de edición de perfil cargado');

    // Validación en tiempo real
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input[required], select[required]');

    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });
    });
});
</script>
@endpush
@endsection
