@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Mis Suscripciones</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Estudiante</th>
                                    <th>Plan</th>
                                    <th>Estado</th>
                                    <th>Fecha de Inicio</th>
                                    <th>Fecha de Fin</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscriptions as $subscription)
                                <tr>
                                    <td>{{ $subscription->subscription_id }}</td>
                                    <td>{{ $subscription->student->first_name }} {{ $subscription->student->last_name }}</td>
                                    <td>{{ $subscription->plan_type }}</td>
                                    <td>
                                        <span class="badge badge-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'pending' ? 'warning' : ($subscription->status === 'expired' ? 'danger' : 'secondary')) }}">
                                            {{ ucfirst($subscription->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $subscription->start_date }}</td>
                                    <td>{{ $subscription->end_date }}</td>
                                    <td>
                                        <a href="{{ route('provider.subscriptions.show', $subscription) }}" class="btn btn-sm btn-info">Ver</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $subscriptions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
