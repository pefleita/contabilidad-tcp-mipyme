<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->string('categoria_producto', 100)->nullable();
            $table->string('codigo', 50);
            $table->string('nombre', 255);
            $table->text('descripcion')->nullable();
            $table->string('unidad_medida', 20)->default('und');
            $table->decimal('precio_costo', 12, 2)->default(0);
            $table->decimal('precio_venta', 12, 2)->default(0);
            $table->decimal('existencias', 12, 2)->default(0);
            $table->decimal('stock_minimo', 12, 2)->default(0);
            $table->boolean('es_servicio')->default(false);
            $table->boolean('esta_activo')->default(true);
            $table->timestamps();
            
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->unique(['empresa_id', 'codigo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
