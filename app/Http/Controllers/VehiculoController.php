<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehiculoRequest;
use App\Http\Requests\UpdateVehiculoRequest;
use App\Models\Cliente;
use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VehiculoController extends Controller
{
    /**
     * Muestra una lista paginada de vehículos con búsqueda opcional.
     */
    public function index(Request $request): View
    {
        //PARAMETRO DE BUSQUEDA, SI USA EL BUSCAR
        $busqueda = $request->input('busqueda');

        //LISTADO DE 10 VEHICULOS
        //TENER EN CUENTA QUE TAMBIEN SE PUEDE USAR DATATABLES
        $vehiculos = Vehiculo::with('cliente')
            ->search($busqueda)
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('vehiculos.index', compact('vehiculos', 'busqueda'));
    }

    /**
     * Mostrar el formulario de creación de un nuevo vehículo + cliente.
     */
    public function create(): View
    {
        return view('vehiculos.create');
    }

    /**
     * Guarda un vehículo y un cliente recién creados en la base de datos.
     */
    public function store(StoreVehiculoRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $client = Cliente::create($request->only([
                'Nombres', 'Apellidos', 'TipoDocumento',
                'NroDocumento', 'Correo', 'Telefono',
            ]));

            $client->vehiculos()->create($request->only([
                'placa', 'marca', 'modelo', 'anio_fabricacion',
                'color', 'vin', 'observaciones',
            ]));
        });

        return redirect()
            ->route('vehiculos.index')
            ->with('success', 'Vehículo y cliente registrados correctamente.');
    }

    /**
     * Muestra el vehículo especificado con los datos del cliente.
     */
    public function show(Vehiculo $vehiculo): View
    {
        $vehiculo->load('cliente');
        return view('vehiculos.show', compact('vehiculo'));
    }

    /**
     * Muestra el formulario para editar el vehículo especificado.
     */
    public function edit(Vehiculo $vehiculo): View
    {
        $vehiculo->load('cliente');
        return view('vehiculos.edit', compact('vehiculo'));
    }

    /**
     * Actualiza el vehículo especificado y su cliente asociado.
     */
    public function update(UpdateVehiculoRequest $request, Vehiculo $vehiculo): RedirectResponse
    {
        DB::transaction(function () use ($request, $vehiculo) {
            $vehiculo->cliente->update($request->only([
                'Nombres', 'Apellidos', 'TipoDocumento',
                'NroDocumento', 'Correo', 'Telefono',
            ]));

            $vehiculo->update($request->only([
                'placa', 'marca', 'modelo', 'anio_fabricacion',
                'color', 'vin', 'observaciones',
            ]));
        });

        return redirect()
            ->route('vehiculos.show', $vehiculo)
            ->with('success', 'Vehículo actualizado correctamente.');
    }

    /**
     * Eliminación temporal del vehículo especificado. / Sigue almacenado en la BD
     */
    public function destroy(Vehiculo $vehiculo): RedirectResponse
    {
        $vehiculo->delete();

        return redirect()
            ->route('vehiculos.index')
            ->with('success', 'Vehículo eliminado correctamente.');
    }
}
