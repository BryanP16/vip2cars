<?php

namespace App\Http\Requests;

class UpdateVehiculoRequest extends StoreVehiculoRequest
{
    // Hereda todas las reglas de StoreVehicleRequest. 
    // Las llamadas ignore() en reglas() ya manejan el escenario de actualización 
    // leyendo $this->route('vehicle')->id y client_id.
}
