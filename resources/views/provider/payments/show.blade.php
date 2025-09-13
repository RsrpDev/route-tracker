@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Detalle de Pago</h2>
                    <a href="{{ route('provider.payments.index') }}" class="btn btn-secondary">Volver</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Información del Estudiante</h5>
                            <p><strong>Nombre:</strong> {{ $payment->student->first_name }} {{ $payment->student->last_name }}</p>
                            <p><strong>Fecha de Nacimiento:</strong> {{ $payment->student->date_of_birth }}</p>
                            <p><strong>Nivel Grado:</strong> {{ $payment->student->grade_level }}</p>
                            <p><strong>Estado:</strong> {{ ucfirst($payment->student->student_status) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Información del Pago</h5>
                            <p><strong>Estado:</strong>
                                <span class="badge badge-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : ($payment->status === 'failed' ? 'danger' : 'secondary')) }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </p>
                            <p><strong>Monto:</strong> ${{ number_format($payment->amount, 2) }}</p>
                            <p><strong>Método de Pago:</strong> {{ $payment->payment_method }}</p>
                            <p><strong>Fecha de Pago:</strong> {{ $payment->payment_date }}</p>
                            <p><strong>Fecha de Vencimiento:</strong> {{ $payment->due_date }}</p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Información de la Suscripción</h5>
                            <p><strong>Precio:</strong> ${{ number_format($payment->subscription->price_snapshot, 2) }}</p>
                            <p><strong>Estado:</strong> {{ ucfirst($payment->subscription->subscription_status) }}</p>
                            <p><strong>Próxima Facturación:</strong> {{ $payment->subscription->next_billing_date }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Información de la Ruta</h5>
                            <p><strong>Nombre:</strong> {{ $payment->subscription->route->route_name }}</p>
                            <p><strong>Inicio:</strong> {{ $payment->subscription->route->start_location }}</p>
                            <p><strong>Fin:</strong> {{ $payment->subscription->route->end_location }}</p>
                            <p><strong>Estado:</strong> {{ ucfirst($payment->subscription->route->route_status) }}</p>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
