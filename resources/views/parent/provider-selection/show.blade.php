@extends('layouts.app')

@section('title', 'Detalles del Proveedor - ' . $provider->display_name)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $provider->display_name }}</h1>
                    <p class="mt-2 text-gray-600">Información detallada del proveedor de transporte</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('parent.provider-selection.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Volver a Proveedores
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información Principal -->
            <div class="lg:col-span-2">
                <!-- Información del Proveedor -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Información del Proveedor</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 h-16 w-16">
                                    <div class="h-16 w-16 rounded-full bg-green-500 flex items-center justify-center">
                                        @switch($provider->provider_type)
                                            @case('driver')
                                                <i class="fas fa-user text-white text-2xl"></i>
                                                @break
                                            @case('company')
                                                <i class="fas fa-building text-white text-2xl"></i>
                                                @break
                                            @case('school_provider')
                                                <i class="fas fa-school text-white text-2xl"></i>
                                                @break
                                            @default
                                                <i class="fas fa-truck text-white text-2xl"></i>
                                        @endswitch
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $provider->display_name }}</h3>
                                    <p class="text-sm text-gray-500">
                                        @switch($provider->provider_type)
                                            @case('driver')
                                                Conductor Independiente
                                                @break
                                            @case('company')
                                                Empresa de Transporte
                                                @break
                                            @case('school_provider')
                                                Colegio Prestador
                                                @break
                                            @default
                                                {{ $provider->provider_type }}
                                        @endswitch
                                    </p>
                                </div>
                            </div>

                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email de Contacto</dt>
                                    <dd class="text-sm text-gray-900">{{ $provider->contact_email ?? 'No especificado' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Teléfono de Contacto</dt>
                                    <dd class="text-sm text-gray-900">{{ $provider->contact_phone ?? 'No especificado' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $provider->provider_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($provider->provider_status) }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Estadísticas</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-3 bg-blue-50 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600">{{ $stats['total_routes'] }}</div>
                                    <div class="text-sm text-blue-800">Rutas Activas</div>
                                </div>
                                <div class="text-center p-3 bg-green-50 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600">{{ $stats['total_students'] }}</div>
                                    <div class="text-sm text-green-800">Estudiantes</div>
                                </div>
                                <div class="text-center p-3 bg-yellow-50 rounded-lg">
                                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['average_rating'] }}</div>
                                    <div class="text-sm text-yellow-800">Calificación</div>
                                </div>
                                <div class="text-center p-3 bg-purple-50 rounded-lg">
                                    <div class="text-2xl font-bold text-purple-600">{{ $stats['years_experience'] }}</div>
                                    <div class="text-sm text-purple-800">Años Exp.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rutas Disponibles -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Rutas Disponibles</h2>

                    @if($routes->count() > 0)
                        <div class="space-y-4">
                            @foreach($routes as $route)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-medium text-gray-900">{{ $route->route_name }}</h3>
                                            <div class="mt-2 space-y-1">
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-map-marker-alt w-4 h-4 mr-2 text-green-500"></i>
                                                    <span>{{ $route->origin_address }}</span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-map-marker-alt w-4 h-4 mr-2 text-red-500"></i>
                                                    <span>{{ $route->destination_address }}</span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-users w-4 h-4 mr-2 text-blue-500"></i>
                                                    <span>Capacidad: {{ $route->capacity }} estudiantes</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-green-600">${{ number_format($route->monthly_price, 0) }}</div>
                                            <div class="text-sm text-gray-500">por mes</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">
                                <i class="fas fa-route"></i>
                            </div>
                            <p class="text-gray-500">No hay rutas disponibles en este momento.</p>
                        </div>
                    @endif
                </div>

                <!-- Planes de Pago -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Planes de Pago Disponibles</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($paymentPlans as $planType => $plan)
                            @php
                                $monthlyPrice = $provider->routes->min('monthly_price') ?: 150000;
                                $savings = 0;
                                if ($planType === 'quarterly') {
                                    $savings = ($monthlyPrice * 3) - $plan['price'];
                                } elseif ($planType === 'annual') {
                                    $savings = ($monthlyPrice * 12) - $plan['price'];
                                }
                            @endphp

                            <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition-colors {{ $planType === 'monthly' ? 'ring-2 ring-indigo-200' : '' }}">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">{{ $plan['name'] }}</h3>
                                        <p class="text-sm text-gray-600">{{ $plan['description'] }}</p>
                                    </div>
                                    @if($plan['discount_rate'] > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $plan['discount_rate'] }}% descuento
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <div class="text-2xl font-bold text-gray-900">${{ number_format($plan['price'], 0) }}</div>
                                    <div class="text-sm text-gray-500">
                                        @if($planType === 'monthly')
                                            por mes
                                        @elseif($planType === 'quarterly')
                                            cada 3 meses
                                        @elseif($planType === 'annual')
                                            por año completo
                                        @elseif($planType === 'postpaid')
                                            después del servicio
                                        @endif
                                    </div>
                                    @if($savings > 0)
                                        <div class="text-sm text-green-600 font-medium">
                                            Ahorro: ${{ number_format($savings, 0) }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <h4 class="text-sm font-medium text-gray-900 mb-1">Características:</h4>
                                    <ul class="text-sm text-gray-600 space-y-1">
                                        @foreach($plan['features'] as $feature)
                                            <li class="flex items-center">
                                                <i class="fas fa-check w-3 h-3 mr-2 text-green-500"></i>
                                                {{ $feature }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="text-xs text-gray-500 mb-3">
                                    Próximo pago: {{ $plan['next_billing_date']->format('d/m/Y') }}
                                </div>

                                @if($planType === 'monthly')
                                    <div class="text-xs text-indigo-600 font-medium">
                                        <i class="fas fa-star mr-1"></i>Plan Recomendado
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Información Adicional -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-500"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Información Importante</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Los precios mostrados son por estudiante y por ruta seleccionada</li>
                                        <li>Los descuentos se aplican automáticamente al seleccionar planes trimestrales o anuales</li>
                                        <li>El plan pospago permite pagar después de recibir el servicio</li>
                                        <li>Todos los planes incluyen renovación automática (puedes desactivarla)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="space-y-6">
                <!-- Seleccionar Estudiante -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Crear Contrato</h2>

                    @if($students->count() > 0)
                        <form method="GET" action="{{ route('parent.provider-selection.create-contract', $provider) }}">
                            <div class="space-y-4">
                                <div>
                                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">Seleccionar Estudiante</label>
                                    <select name="student_id" id="student_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Selecciona un estudiante</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->student_id }}">
                                                {{ $student->given_name }} {{ $student->family_name }} - Grado {{ $student->grade }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="route_id" class="block text-sm font-medium text-gray-700 mb-1">Seleccionar Ruta</label>
                                    <select name="route_id" id="route_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Selecciona una ruta</option>
                                        @foreach($routes as $route)
                                            <option value="{{ $route->route_id }}">
                                                {{ $route->route_name }} - ${{ number_format($route->monthly_price, 0) }}/mes
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="payment_plan_type" class="block text-sm font-medium text-gray-700 mb-1">Plan de Pago</label>
                                    <select name="payment_plan_type" id="payment_plan_type" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Selecciona un plan</option>
                                        @foreach($paymentPlans as $planType => $plan)
                                            <option value="{{ $planType }}">
                                                {{ $plan['name'] }} - ${{ number_format($plan['price'], 0) }}
                                                @if($plan['discount_rate'] > 0)
                                                    ({{ $plan['discount_rate'] }}% descuento)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-file-contract mr-2"></i>Crear Contrato
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-4">
                            <div class="text-gray-400 text-3xl mb-2">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <p class="text-gray-500 text-sm">No tienes estudiantes registrados.</p>
                            <a href="{{ route('students.create') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                Registrar estudiante
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Información de Contacto -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Contacto</h2>

                    <div class="space-y-3">
                        @if($provider->contact_email)
                            <div class="flex items-center">
                                <i class="fas fa-envelope w-4 h-4 mr-3 text-gray-400"></i>
                                <a href="mailto:{{ $provider->contact_email }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                    {{ $provider->contact_email }}
                                </a>
                            </div>
                        @endif

                        @if($provider->contact_phone)
                            <div class="flex items-center">
                                <i class="fas fa-phone w-4 h-4 mr-3 text-gray-400"></i>
                                <a href="tel:{{ $provider->contact_phone }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                    {{ $provider->contact_phone }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Reseñas (Simuladas) -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Reseñas</h2>

                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="flex text-yellow-400">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                            </div>
                            <span class="ml-2 text-sm text-gray-600">4.5/5 ({{ rand(10, 50) }} reseñas)</span>
                        </div>

                        <div class="text-sm text-gray-600">
                            "Excelente servicio, muy puntual y seguro."
                        </div>
                        <div class="text-xs text-gray-500">- María González</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
