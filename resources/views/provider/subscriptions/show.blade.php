@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Detalle de Suscripción</h2>
                    <a href="{{ route('provider.subscriptions.index') }}" class="btn btn-secondary">Volver</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Información del Estudiante</h5>
                            <p><strong>Nombre:</strong> {{ $subscription->student->first_name }} {{ $subscription->student->last_name }}</p>
                            <p><strong>Fecha de Nacimiento:</strong> {{ $subscription->student->date_of_birth }}</p>
                            <p><strong>Nivel Grado:</strong> {{ $subscription->student->grade_level }}</p>
                            <p><strong>Estado:</strong> {{ ucfirst($subscription->student->student_status) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Información de la Suscripción</h5>
                            <p><strong>Estado:</strong>
                                <span class="badge badge-{{ $subscription->subscription_status === 'active' ? 'success' : ($subscription->subscription_status === 'pending' ? 'warning' : ($subscription->subscription_status === 'expired' ? 'danger' : 'secondary')) }}">
                                    {{ ucfirst($subscription->subscription_status) }}
                                </span>
                            </p>
                            <p><strong>Ciclo de Facturación:</strong> {{ $subscription->billing_cycle }}</p>
                            <p><strong>Precio:</strong> ${{ number_format($subscription->price_snapshot, 2) }}</p>
                            <p><strong>Próxima Facturación:</strong> {{ $subscription->next_billing_date }}</p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Información de Pago</h5>
                            <p><strong>Precio:</strong> ${{ number_format($subscription->price_snapshot, 2) }}</p>
                            <p><strong>Tarifa Plataforma:</strong> {{ $subscription->platform_fee_rate }}%</p>
                            <p><strong>Estado de Pago:</strong> Pendiente</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Información de la Ruta</h5>
                            <p><strong>Nombre:</strong> {{ $subscription->route->route_name }}</p>
                            <p><strong>Origen:</strong> {{ $subscription->route->start_location }}</p>
                            <p><strong>Destino:</strong> {{ $subscription->route->end_location }}</p>
                            <p><strong>Estado:</strong> {{ ucfirst($subscription->route->route_status) }}</p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Acciones</h5>
                            <a href="{{ route('provider.subscriptions.index') }}" class="btn btn-primary">Ver Todas las Suscripciones</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
