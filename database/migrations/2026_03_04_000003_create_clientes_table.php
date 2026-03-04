<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('Nombres', 100);
            $table->string('Apellidos', 100);
            $table->enum('TipoDocumento', ['DNI', 'CE', 'RUC', 'PASSPORT'])->default('DNI');
            $table->string('NroDocumento', 20);
            $table->string('Correo', 150)->unique();
            $table->string('Telefono', 20);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['TipoDocumento', 'NroDocumento']);
            $table->index(['Nombres', 'Apellidos']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
