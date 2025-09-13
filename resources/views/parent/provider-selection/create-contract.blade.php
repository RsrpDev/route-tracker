@extends('layouts.app')

@section('title', 'Crear Contrato de Transporte')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Crear Contrato de Transporte</h1>
                    <p class="mt-2 text-gray-600">Confirma los detalles del contrato con {{ $provider->display_name }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('parent.provider-selection.show', $provider) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Formulario -->
            <div class="lg:col-span-2">
                <form method="POST" action="{{ route('parent.provider-selection.store-contract', $provider) }}">
                    @csrf

                    <!-- Información del Estudiante -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Información del Estudiante</h2>

                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 h-12 w-12">
                                <div class="h-12 w-12 rounded-full bg-indigo-500 flex items-center justify-center">
                                    <span class="text-lg font-medium text-white">
                                        {{ substr($student->given_name, 0, 1) }}{{ substr($student->family_name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ $student->given_name }} {{ $student->family_name }}
                                </h3>
                                <p class="text-sm text-gray-500">Grado {{ $student->grade }} - {{ $student->school->legal_name ?? 'Escuela no especificada' }}</p>
                            </div>
                        </div>

                        <input type="hidden" name="student_id" value="{{ $student->student_id }}">
                    </div>

                    <!-- Información de la Ruta -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Ruta Seleccionada</h2>

                        <div class="border border-gray-200 rounded-lg p-4">
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

                        <input type="hidden" name="route_id" value="{{ $route->route_id }}">
                    </div>

                    <!-- Plan de Pago -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Plan de Pago Seleccionado</h2>

                        @php
                            $planType = request('payment_plan_type');
                            $monthlyPrice = $route->monthly_price;
                            $savings = 0;
                            $originalPrice = 0;

                            if ($planType === 'quarterly') {
                                $originalPrice = $monthlyPrice * 3;
                                $savings = $originalPrice - $paymentPlan['price'];
                            } elseif ($planType === 'annual') {
                                $originalPrice = $monthlyPrice * 12;
                                $savings = $originalPrice - $paymentPlan['price'];
                            } else {
                                $originalPrice = $monthlyPrice;
                            }
                        @endphp

                        <div class="border border-gray-200 rounded-lg p-4 {{ $planType === 'monthly' ? 'ring-2 ring-indigo-200' : '' }}">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ $paymentPlan['name'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $paymentPlan['description'] }}</p>
                                </div>
                                @if($paymentPlan['discount_rate'] > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $paymentPlan['discount_rate'] }}% descuento
                                    </span>
                                @endif
                            </div>

                            <div class="mb-3">
                                <div class="flex items-baseline">
                                    <div class="text-2xl font-bold text-gray-900">${{ number_format($paymentPlan['price'], 0) }}</div>
                                    <div class="text-sm text-gray-500 ml-2">
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
                                </div>

                                @if($savings > 0)
                                    <div class="mt-1">
                                        <span class="text-sm text-gray-500 line-through">${{ number_format($originalPrice, 0) }}</span>
                                        <span class="text-sm text-green-600 font-medium ml-2">
                                            Ahorro: ${{ number_format($savings, 0) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <h4 class="text-sm font-medium text-gray-900 mb-1">Características:</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    @foreach($paymentPlan['features'] as $feature)
                                        <li class="flex items-center">
                                            <i class="fas fa-check w-3 h-3 mr-2 text-green-500"></i>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="text-xs text-gray-500 mb-2">
                                Próximo pago: {{ $paymentPlan['next_billing_date']->format('d/m/Y') }}
                            </div>

                            @if($planType === 'monthly')
                                <div class="text-xs text-indigo-600 font-medium">
                                    <i class="fas fa-star mr-1"></i>Plan Recomendado
                                </div>
                            @endif
                        </div>

                        <input type="hidden" name="payment_plan_type" value="{{ request('payment_plan_type') }}">
                    </div>

                    <!-- Instrucciones Especiales -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Instrucciones Especiales</h2>

                        <div>
                            <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-1">
                                Instrucciones adicionales (opcional)
                            </label>
                            <textarea name="special_instructions" id="special_instructions" rows="4"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Ej: Horario especial, dirección de recogida alternativa, contacto de emergencia, etc."></textarea>
                            <p class="mt-1 text-sm text-gray-500">Máximo 500 caracteres</p>
                        </div>
                    </div>

                    <!-- Términos y Condiciones -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Términos y Condiciones</h2>

                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <h3 class="font-medium text-gray-900 mb-2">Términos del Contrato:</h3>
                            <ul class="text-sm text-gray-700 space-y-1">
                                <li>• El contrato tiene una duración de 12 meses</li>
                                <li>• El pago se realizará según el plan seleccionado</li>
                                <li>• El servicio está sujeto a disponibilidad de rutas</li>
                                <li>• Se requiere notificación con 30 días de anticipación para cancelación</li>
                                <li>• El proveedor se compromete a mantener la seguridad del estudiante</li>
                                <li>• Los horarios pueden variar según condiciones del tráfico</li>
                            </ul>
                        </div>

                        <div class="flex items-start">
                            <input type="checkbox" name="agree_terms" id="agree_terms" required
                                class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="agree_terms" class="ml-2 text-sm text-gray-700">
                                Acepto los términos y condiciones del contrato de transporte
                            </label>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('parent.provider-selection.show', $provider) }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-file-contract mr-2"></i>Crear Contrato
                        </button>
                    </div>
                </form>
            </div>

            <!-- Resumen -->
            <div class="space-y-6">
                <!-- Resumen del Contrato -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Resumen del Contrato</h2>

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Estudiante:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $student->given_name }} {{ $student->family_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Proveedor:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $provider->display_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Ruta:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $route->route_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Plan de Pago:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $paymentPlan['name'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Precio Base:</span>
                            <span class="text-sm font-medium text-gray-900">${{ number_format($route->monthly_price, 0) }}/mes</span>
                        </div>
                        @if($paymentPlan['discount_rate'] > 0)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Descuento:</span>
                                <span class="text-sm font-medium text-green-600">-{{ $paymentPlan['discount_rate'] }}%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Ahorro:</span>
                                <span class="text-sm font-medium text-green-600">${{ number_format($savings, 0) }}</span>
                            </div>
                        @endif
                        <hr class="my-2">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-900">Total {{ $planType === 'monthly' ? 'mensual' : ($planType === 'quarterly' ? 'trimestral' : ($planType === 'annual' ? 'anual' : 'pospago')) }}:</span>
                            <span class="text-lg font-bold text-gray-900">${{ number_format($paymentPlan['price'], 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Información del Proveedor -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Proveedor</h2>

                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-green-500 flex items-center justify-center">
                                @switch($provider->provider_type)
                                    @case('driver')
                                        <i class="fas fa-user text-white"></i>
                                        @break
                                    @case('company')
                                        <i class="fas fa-building text-white"></i>
                                        @break
                                    @case('school_provider')
                                        <i class="fas fa-school text-white"></i>
                                        @break
                                    @default
                                        <i class="fas fa-truck text-white"></i>
                                @endswitch
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">{{ $provider->display_name }}</h3>
                            <p class="text-xs text-gray-500">
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

                    @if($provider->contact_email)
                        <div class="flex items-center text-sm text-gray-600 mb-1">
                            <i class="fas fa-envelope w-4 h-4 mr-2"></i>
                            {{ $provider->contact_email }}
                        </div>
                    @endif

                    @if($provider->contact_phone)
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-phone w-4 h-4 mr-2"></i>
                            {{ $provider->contact_phone }}
                        </div>
                    @endif
                </div>

                <!-- Próximos Pasos -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-blue-900 mb-2">Próximos Pasos</h3>
                    <ol class="text-sm text-blue-800 space-y-1">
                        <li>1. Confirma los detalles del contrato</li>
                        <li>2. Acepta los términos y condiciones</li>
                        <li>3. El proveedor revisará tu solicitud</li>
                        <li>4. Recibirás confirmación por email</li>
                        <li>5. El servicio comenzará según lo acordado</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
