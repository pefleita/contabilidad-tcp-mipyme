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
        Schema::create('kardex', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->enum('tipo_movimiento', ['entrada', 'salida', 'ajuste']);
            $table->enum('tipo_origen', ['compra', 'venta', 'devolucion', 'ajuste', 'produccion'])->nullable();
            $table->decimal('cantidad', 12, 2);
            $table->decimal('precio_unitario', 12, 2)->default(0);
            $table->decimal('costo_total', 12, 2)->default(0);
            $table->decimal('existencias_anterior', 12, 2)->default(0);
            $table->decimal('existencias_nueva', 12, 2)->default(0);
            $table->date('fecha');
            $table->string('referencia', 100)->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
            
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
            $table->index('fecha');
            $table->index('producto_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kardex');
    }
};
