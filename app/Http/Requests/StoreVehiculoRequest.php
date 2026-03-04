<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehiculoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        //$vehicleId = $this->route('vehiculo')?->id;

        // Obtiene el ID directamente sin depender del modelo
        $vehiculoId = $this->route('vehiculo');
        $vehiculoId = is_object($vehiculoId) ? $vehiculoId->id : $vehiculoId;

        // Para obtener el id_cliente
        /*$idCliente = is_object($this->route('vehiculo')) 
            ? $this->route('vehiculo')->id_cliente 
            : null;*/ 
         // Busca el vehículo actual para obtener el id_cliente
        $vehiculo = \App\Models\Vehiculo::find($vehiculoId);
        $idCliente = $vehiculo?->id_cliente;

        return [
            // ── Validaciones para los campos de Cliente ──
            'Nombres'      => ['required', 'string', 'max:100'],
            'Apellidos'       => ['required', 'string', 'max:100'],
            'TipoDocumento'   => ['required', Rule::in(['DNI', 'CE', 'RUC', 'PASSPORT'])],
            //'NroDocumento' => ['required', 'string', 'max:20'],
            'NroDocumento' => [
                'required',
                Rule::unique('clientes')
                    ->where('TipoDocumento', $this->TipoDocumento)->ignore($idCliente),
            ],
            'Correo'           => [
                'required', 'email:rfc,dns', 'max:150',
                Rule::unique('clientes', 'Correo')->ignore($idCliente),
            ],
            'Telefono'           => ['required', 'string', 'max:20', 'regex:/^[\+\d\s\-\(\)]{7,20}$/'],

            // ── Validaciones para los campos de vehiculo ──
            'placa'            => [
                'required', 'string', 'max:10', 'regex:/^[A-Z0-9\-]{2,10}$/i',
                Rule::unique('vehiculos', 'placa')->ignore($vehiculoId)->whereNull('deleted_at'),
            ],
            'marca'            => ['required', 'string', 'max:80'],
            'modelo'            => ['required', 'string', 'max:80'],
            'anio_fabricacion' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'color'            => ['nullable', 'string', 'max:40'],
            'vin'              => ['nullable', 'string', 'size:17', 'alpha_num'],
            'observaciones'            => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'               => 'El :attribute es requerido',
            'Apellidos.required'     => 'Los Apellidos son requeridos',
            'placa.required'         => 'La placa es requerida',
            'marca.required'         => 'La marca es requerida',
            'placa.regex'            => 'La placa solo puede contener letras, números y guiones.',
            'Telefono.regex'         => 'El teléfono no tiene un formato válido.',
            'anio_fabricacion.min'   => 'El año de fabricación no puede ser anterior a 1900.',
            'anio_fabricacion.max'   => 'El año de fabricación no puede ser futuro.',
            'vin.size'               => 'El VIN debe tener exactamente 17 caracteres.',
            'vin.alpha_num'          => 'El VIN solo puede contener letras y números.',
            'NroDocumento.unique'    => 'El cliente ya fue registrado',
            'Correo.unique'           => 'Ya existe un cliente con ese correo electrónico.',
            'placa.unique'           => 'Ya existe un vehículo registrado con esa placa.',
        ];
    }

    public function attributes(): array
    {
        return [
            'Nombres'           => 'nombre',
            'Apellidos'         => 'apellidos',
            'TipoDocumento'     => 'tipo de documento',
            'NroDocumento'      => 'número de documento',
            'Correo'            => 'correo electrónico',
            'Telefono'          => 'teléfono',
            'placa'             => 'placa',
            'marca'             => 'marca',
            'modelo'            => 'modelo',
            'anio_fabricacion'  => 'año de fabricación',
        ];
    }
}
