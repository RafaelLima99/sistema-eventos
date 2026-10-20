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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('titulo', 255);
            $table->string('local', 255);
            $table->decimal('preco_ingresso', 10, 2)->default(0);
            $table->string('endereco', 255)->nullable();
            $table->string('cidade', 120);
            $table->char('uf', 2);
            $table->string('status', 20)->default('rascunho');
            $table->date('data_evento');
            $table->time('hora_inicio');
            $table->time('hora_fim')->nullable();
            $table->string('link_inscricao', 2048)->nullable();
            $table->string('link_pagamento', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
