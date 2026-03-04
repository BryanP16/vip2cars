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
     * Display a paginated listing of vehicles with optional search.
     */
    public function index(Request $request): View
    {
        //PARAMETRO DE BUSQUEDA, SI USA EL BUSCAR
        $busqueda = $request->input('search');

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
     * Show the form for creating a new vehicle + client.
     */
    public function create(): View
    {
        return view('vehiculos.create');
    }

    /**
     * Store a newly created vehicle and client in the database.
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
     * Display the specified vehicle with client details.
     */
    public function show(Vehiculo $vehiculo): View
    {
        $vehiculo->load('cliente');
        return view('vehiculos.show', compact('vehiculo'));
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit(Vehiculo $vehiculo): View
    {
        $vehiculo->load('cliente');
        return view('vehiculos.edit', compact('vehiculo'));
    }

    /**
     * Update the specified vehicle and its associated client.
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
     * Soft-delete the specified vehicle.
     */
    public function destroy(Vehiculo $vehiculo): RedirectResponse
    {
        $vehiculo->delete();

        return redirect()
            ->route('vehiculos.index')
            ->with('success', 'Vehículo eliminado correctamente.');
    }
}
