<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehiculo extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'vehiculos';

    protected $fillable = [
        'id_cliente',
        'placa',
        'marca',
        'modelo',
        'anio_fabricacion',
        'color',
        'vin',
        'observaciones',
    ];

    protected $casts = [
        'anio_fabricacion' => 'integer',
        'id_cliente'        => 'integer',
    ];

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    // ──────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('placa', 'like', "%{$term}%")
              ->orWhere('marca', 'like', "%{$term}%")
              ->orWhere('modelo', 'like', "%{$term}%")
              ->orWhere('anio_fabricacion', 'like', "%{$term}%")
              ->orWhereHas('cliente', function ($cq) use ($term) {
                  $cq->where('Nombres', 'like', "%{$term}%")
                     ->orWhere('Apellidos', 'like', "%{$term}%")
                     ->orWhere('NroDocumento', 'like', "%{$term}%");
              });
        });
    }
}
