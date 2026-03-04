@extends('layouts.app')
@section('title', $vehiculo->placa . ' — Detalle')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <a href="{{ route('vehiculos.index') }}" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
        <h4 class="fw-bold mt-1">Detalle del Vehículo</h4>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i> Editar
        </a>
        <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST"
              onsubmit="return confirm('¿Confirma eliminar este vehículo?')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger"><i class="bi bi-trash me-1"></i> Eliminar</button>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card text-center p-4">
            <div class="display-5 mb-3">🚗</div>
            <h2 class="badge-plate d-inline-block mb-2">{{ $vehiculo->placa }}</h2>
            <h5 class="fw-bold mt-2">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</h5>
            <p class="text-muted mb-0">{{ $vehiculo->anio_fabricacion }}
                @if($vehiculo->color) · {{ $vehiculo->color }} @endif
            </p>
            @if($vehiculo->vin)
            <p class="mt-2 small text-muted">VIN: <code>{{ $vehiculo->vin }}</code></p>
            @endif
            @if($vehiculo->observaciones)
            <div class="mt-3 text-start">
                <div class="section-title">Notas</div>
                <p class="small text-muted">{{ $vehiculo->observaciones }}</p>
            </div>
            @endif
            <p class="mt-3 small text-muted">Registrado: {{ $vehiculo->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <div class="section-title">👤 Datos del Cliente</div>
                <table class="table table-borderless mb-0">
                    <tr>
                        <th class="text-muted fw-normal" width="40%">Nombre completo</th>
                        <td class="fw-semibold">{{ $vehiculo->cliente->Nombres }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Documento</th>
                        <td>{{ $vehiculo->cliente->TipoDocumento }}: {{ $vehiculo->cliente->NroDocumento }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Correo</th>
                        <td><a href="mailto:{{ $vehiculo->cliente->Correo }}">{{ $vehiculo->cliente->Correo }}</a></td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Teléfono</th>
                        <td><a href="tel:{{ $vehiculo->cliente->Telefono }}">{{ $vehiculo->cliente->Telefono }}</a></td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Otros vehículos</th>
                        <td>
                            @foreach($vehiculo->cliente->vehiculos as $v)
                                @if($v->id !== $vehiculo->id)
                                    <a href="{{ route('vehiculos.show', $v) }}" class="badge-plate d-inline-block mb-1">{{ $v->placa }}</a>
                                @endif
                            @endforeach
                            @if($vehiculo->cliente->vehiculos->count() === 1)
                                <span class="text-muted small">Sin otros vehículos</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
