<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clientes';

    protected $fillable = [
        'Nombres',
        'Apellidos',
        'TipoDocumento',
        'NroDocumento',
        'Correo',
        'Telefono',
    ];

    protected $casts = [
        'TipoDocumento' => 'string',
    ];

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class , 'id_cliente');
    }

    // ──────────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return "{$this->Nombres} {$this->Apellidos}";
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
            $q->where('Nombres', 'like', "%{$term}%")
              ->orWhere('Apellidos', 'like', "%{$term}%")
              ->orWhere('NroDocumento', 'like', "%{$term}%")
              ->orWhere('Correo', 'like', "%{$term}%")
              ->orWhere('Telefono', 'like', "%{$term}%");
        });
    }
}
