@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Detalle del Conductor</h2>
                    <a href="{{ route('provider.drivers.index') }}" class="btn btn-secondary">Volver</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Información Personal</h5>
                            <p><strong>ID:</strong> {{ $driver->driver_id }}</p>
                            <p><strong>Nombre:</strong> {{ $driver->first_name }} {{ $driver->last_name }}</p>
                            <p><strong>Fecha de Nacimiento:</strong> {{ $driver->date_of_birth }}</p>
                            <p><strong>Género:</strong> {{ $driver->gender }}</p>
                            <p><strong>Estado:</strong>
                                <span class="badge badge-{{ $driver->status === 'active' ? 'success' : ($driver->status === 'pending' ? 'warning' : ($driver->status === 'on_leave' ? 'info' : 'secondary')) }}">
                                    {{ ucfirst($driver->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>Información de Licencia</h5>
                            <p><strong>Número de Licencia:</strong> {{ $driver->license_number }}</p>
                            <p><strong>Tipo de Licencia:</strong> {{ $driver->license_type }}</p>
                            <p><strong>Fecha de Vencimiento:</strong> {{ $driver->license_expiry }}</p>
                            <p><strong>Estado de Licencia:</strong>
                                @if($driver->license_expiry && $driver->license_expiry < now())
                                    <span class="badge badge-danger">Vencida</span>
                                @else
                                    <span class="badge badge-success">Válida</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Información de Contacto</h5>
                            <p><strong>Teléfono:</strong> {{ $driver->phone_number }}</p>
                            <p><strong>Email:</strong> {{ $driver->email }}</p>
                            <p><strong>Dirección:</strong> {{ $driver->address }}</p>
                            <p><strong>Ciudad:</strong> {{ $driver->city }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Información de Emergencia</h5>
                            <p><strong>Contacto de Emergencia:</strong> {{ $driver->emergency_contact }}</p>
                            <p><strong>Teléfono de Emergencia:</strong> {{ $driver->emergency_phone }}</p>
                            <p><strong>Relación:</strong> {{ $driver->emergency_relationship }}</p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Vehículo Asignado</h5>
                            @if($driver->vehicle)
                                <p><strong>Placa:</strong> {{ $driver->vehicle->license_plate }}</p>
                                <p><strong>Marca:</strong> {{ $driver->vehicle->make }}</p>
                                <p><strong>Modelo:</strong> {{ $driver->vehicle->model }}</p>
                                <p><strong>Estado:</strong> {{ ucfirst($driver->vehicle->status) }}</p>
                            @else
                                <p class="text-muted">No hay vehículo asignado</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h5>Rutas Asignadas</h5>
                            @if($driver->routes && count($driver->routes) > 0)
                                <ul class="list-group">
                                    @foreach($driver->routes as $route)
                                    <li class="list-group-item">
                                        <strong>{{ $route->route_name }}</strong><br>
                                        <small>{{ $route->origin }} → {{ $route->destination }}</small>
                                    </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">No hay rutas asignadas</p>
                            @endif
                        </div>
                    </div>

                    @if($driver->documents && count($driver->documents) > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Documentos</h5>
                            <ul class="list-group">
                                @foreach($driver->documents as $document)
                                <li class="list-group-item">
                                    <a href="{{ Storage::url($document->file_path) }}" target="_blank">
                                        {{ $document->document_type }} - {{ $document->file_name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
