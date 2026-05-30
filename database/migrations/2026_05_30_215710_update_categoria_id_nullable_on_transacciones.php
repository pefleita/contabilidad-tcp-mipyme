<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transacciones', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
        });

        Schema::table('transacciones', function (Blueprint $table) {
            $table->unsignedBigInteger('categoria_id')->nullable()->change();
        });

        Schema::table('transacciones', function (Blueprint $table) {
            $table->foreign('categoria_id')
                ->references('id')
                ->on('categorias')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transacciones', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
        });

        Schema::table('transacciones', function (Blueprint $table) {
            $table->unsignedBigInteger('categoria_id')->nullable(false)->change();
        });

        Schema::table('transacciones', function (Blueprint $table) {
            $table->foreign('categoria_id')
                ->references('id')
                ->on('categorias');
        });
    }
};
