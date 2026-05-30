<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activos_fijos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->string('codigo', 50);
            $table->string('nombre', 255);
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['mueble', 'inmueble', 'vehiculo', 'equipo', 'otro']);
            $table->decimal('costo_original', 12, 2);
            $table->decimal('valor_residual', 12, 2)->default(0);
            $table->integer('vida_util_anos');
            $table->decimal('depreciacion_acumulada', 12, 2)->default(0);
            $table->date('fecha_adquisicion');
            $table->date('fecha_inicio_depreciacion');
            $table->boolean('esta_activo')->default(true);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activos_fijos');
    }
};
