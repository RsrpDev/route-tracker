{{--
    Archivo: resources/views/provider/drivers/index.blade.php
    Roles: provider
    Rutas necesarias: Route::resource('provider.drivers', ProviderDriverController::class)
--}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Mis Conductores</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Licencia</th>
                                    <th>Vehículo</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($drivers as $driver)
                                <tr>
                                    <td>{{ $driver->driver_id }}</td>
                                    <td>{{ $driver->account->full_name ?? 'N/A' }}</td>
                                    <td>{{ $driver->license_number }}</td>
                                    <td>{{ $driver->vehicle->license_plate ?? 'Sin asignar' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $driver->status === 'active' ? 'success' : ($driver->status === 'suspended' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($driver->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('provider.drivers.show', $driver) }}" class="btn btn-sm btn-info">Ver</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $drivers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
