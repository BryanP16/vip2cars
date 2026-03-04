@extends('layouts.app')
@section('title', 'Vehículos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Vehículos Registrados</h4>
        <small class="text-muted">{{ $vehiculos->total() }} registros encontrados</small>
    </div>
    <a href="{{ route('vehiculos.create') }}" class="btn btn-vip">
        <i class="bi bi-plus-lg me-1"></i> Nuevo
    </a>
</div>

{{-- Search --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('vehiculos.index') }}" method="GET" class="row g-2">
            <div class="col-md-9">
                <input type="text" name="busqueda" class="form-control" placeholder="Buscar por placa, marca, modelo, cliente, documento…" value="{{ $busqueda }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-vip flex-grow-1"><i class="bi bi-search me-1"></i>Buscar</button>
                @if($busqueda)
                    <a href="{{ route('vehiculos.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Marca / Modelo</th>
                    <th>Año</th>
                    <th>Cliente</th>
                    <th>Documento</th>
                    <th>Teléfono</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vehiculos as $vehiculo)
                <tr>
                    <td><span class="badge-plate">{{ $vehiculo->placa }}</span></td>
                    <td>
                        <strong>{{ $vehiculo->marca }}</strong>
                        <span class="text-muted">{{ $vehiculo->modelo }}</span>
                    </td>
                    <td>{{ $vehiculo->anio_fabricacion }}</td>
                    <td>{{ $vehiculo->cliente->Nombres }}</td>
                    <td>
                        <small class="text-muted">{{ $vehiculo->cliente->TipoDocumento }}</small>
                        {{ $vehiculo->cliente->NroDocumento }}
                    </td>
                    <td>{{ $vehiculo->cliente->Telefono }}</td>
                    <td class="text-center">
                        <a href="{{ route('vehiculos.show', $vehiculo) }}" class="btn btn-sm btn-outline-secondary" title="Ver"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('¿Eliminar este vehículo?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-car-front fs-1 d-block mb-2"></i>
                        No se encontraron vehículos.
                        <a href="{{ route('vehiculos.create') }}">Registrar el primero</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($vehiculos->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Página {{ $vehiculos->currentPage() }} de {{ $vehiculos->lastPage() }}
        </small>
        {{ $vehiculos->links() }}
    </div>
    @endif
</div>
@endsection
