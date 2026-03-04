@extends('layouts.app')
@section('title', 'Editar — ' . $vehiculo->plate)

@section('content')
<div class="mb-4">
    <a href="{{ route('vehiculos.show', $vehiculo) }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Volver al detalle
    </a>
    <h4 class="fw-bold mt-1">Editar Vehículo <span class="badge-plate ms-2">{{ $vehiculo->placa }}</span></h4>
</div>

<form action="{{ route('vehiculos.update', $vehiculo) }}" method="POST" novalidate>
    @csrf @method('PUT')
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="section-title">👤 Datos del Cliente</div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="Nombres" class="form-control @error('Nombres') is-invalid @enderror"
                                   value="{{ old('Nombres', $vehiculo->cliente->Nombres) }}" required>
                            @error('Nombres')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Apellidos *</label>
                            <input type="text" name="Apellidos" class="form-control @error('Apellidos') is-invalid @enderror"
                                   value="{{ old('Apellidos', $vehiculo->cliente->Apellidos) }}" required>
                            @error('Apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-5">
                            <label class="form-label">Tipo Doc. *</label>
                            <select name="TipoDocumento" class="form-select @error('TipoDocumento') is-invalid @enderror" required>
                                @foreach(['DNI','CE','RUC','PASSPORT'] as $dtype)
                                    <option value="{{ $dtype }}"
                                        {{ old('TipoDocumento', $vehiculo->cliente->TipoDocumento) === $dtype ? 'selected' : '' }}>{{ $dtype }}</option>
                                @endforeach
                            </select>
                            @error('TipoDocumento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-7">
                            <label class="form-label">Nro. Documento *</label>
                            <input type="text" name="NroDocumento" class="form-control @error('NroDocumento') is-invalid @enderror"
                                   value="{{ old('NroDocumento', $vehiculo->cliente->NroDocumento) }}" required>
                            @error('NroDocumento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Correo Electrónico *</label>
                            <input type="email" name="Correo" class="form-control @error('Correo') is-invalid @enderror"
                                   value="{{ old('Correo', $vehiculo->cliente->Correo) }}" required>
                            @error('Correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Teléfono *</label>
                            <input type="text" name="Telefono" class="form-control @error('Telefono') is-invalid @enderror"
                                   value="{{ old('Telefono', $vehiculo->cliente->Telefono) }}" required>
                            @error('Telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="section-title">🚗 Datos del Vehículo</div>
                    <div class="row g-3">
                        <div class="col-sm-5">
                            <label class="form-label">Placa *</label>
                            <input type="text" name="placa" class="form-control text-uppercase @error('placa') is-invalid @enderror"
                                   value="{{ old('placa', $vehiculo->placa) }}" maxlength="10" required>
                            @error('placa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-7">
                            <label class="form-label">Marca *</label>
                            <input type="text" name="marca" class="form-control @error('marca') is-invalid @enderror"
                                   value="{{ old('marca', $vehiculo->marca) }}" required>
                            @error('marca')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-8">
                            <label class="form-label">Modelo *</label>
                            <input type="text" name="modelo" class="form-control @error('modelo') is-invalid @enderror"
                                   value="{{ old('modelo', $vehiculo->modelo) }}" required>
                            @error('modelo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Año *</label>
                            <input type="number" name="anio_fabricacion" class="form-control @error('anio_fabricacion') is-invalid @enderror"
                                   value="{{ old('anio_fabricacion', $vehiculo->anio_fabricacion) }}" min="1900" max="{{ date('Y')+1 }}" required>
                            @error('anio_fabricacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-5">
                            <label class="form-label">Color</label>
                            <input type="text" name="color" class="form-control @error('color') is-invalid @enderror"
                                   value="{{ old('color', $vehiculo->color) }}">
                            @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-7">
                            <label class="form-label">VIN</label>
                            <input type="text" name="vin" class="form-control @error('vin') is-invalid @enderror"
                                   value="{{ old('vin', $vehiculo->vin) }}" maxlength="17">
                            @error('vin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notas</label>
                            <textarea name="observaciones" class="form-control @error('observaciones') is-invalid @enderror"
                                      rows="3">{{ old('observaciones', $vehiculo->observaciones) }}</textarea>
                            @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 d-flex gap-2 justify-content-end">
            <a href="{{ route('vehiculos.show', $vehiculo) }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-vip px-5">
                <i class="bi bi-save me-1"></i> Actualizar
            </button>
        </div>
    </div>
</form>
@endsection
