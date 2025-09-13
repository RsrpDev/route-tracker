@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Mi Perfil de Proveedor</h2>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('provider.update-profile') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="provider_type">Tipo de Proveedor</label>
                            <input type="text" name="provider_type" id="provider_type" class="form-control @error('provider_type') is-invalid @enderror" value="{{ old('provider_type', $provider->provider_type) }}" required>
                            @error('provider_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="display_name">Nombre de la Empresa</label>
                            <input type="text" name="display_name" id="display_name" class="form-control @error('display_name') is-invalid @enderror" value="{{ old('display_name', $provider->display_name) }}" required>
                            @error('display_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_email">Email de Contacto</label>
                                    <input type="email" name="contact_email" id="contact_email" class="form-control @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $provider->contact_email) }}" required>
                                    @error('contact_email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone">Teléfono de Contacto</label>
                                    <input type="text" name="contact_phone" id="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $provider->contact_phone) }}" required>
                                    @error('contact_phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="linked_school_id">Escuela Vinculada (Opcional)</label>
                            <select name="linked_school_id" id="linked_school_id" class="form-control @error('linked_school_id') is-invalid @enderror">
                                <option value="">Sin escuela vinculada</option>
                                <!-- Aquí se podrían cargar las escuelas disponibles -->
                            </select>
                            @error('linked_school_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="default_commission_rate">Tasa de Comisión por Defecto (%)</label>
                            <input type="number" name="default_commission_rate" id="default_commission_rate" class="form-control @error('default_commission_rate') is-invalid @enderror" value="{{ old('default_commission_rate', $provider->default_commission_rate) }}" min="0" max="100" step="0.01">
                            @error('default_commission_rate')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
