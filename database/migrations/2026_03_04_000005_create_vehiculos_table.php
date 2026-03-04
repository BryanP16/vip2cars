<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cliente')->constrained('clientes')->restrictOnDelete();
            $table->string('placa', 10)->unique();
            $table->string('marca', 80);
            $table->string('modelo', 80);
            $table->year('anio_fabricacion');
            $table->string('color', 40)->nullable();
            $table->string('vin', 17)->nullable()->comment('Número de identificación del vehículo');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('id_cliente');
            $table->index(['marca', 'modelo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
