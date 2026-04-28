<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->string('nombre', 100);
            $table->enum('tipo', ['ingreso', 'gasto']);
            $table->string('color', 20)->default('blue');
            $table->string('icono', 50)->nullable();
            $table->boolean('es_activo')->default(true);
            $table->timestamps();
            
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->index('empresa_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};