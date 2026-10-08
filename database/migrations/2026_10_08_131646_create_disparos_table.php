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
        Schema::create('disparos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('whatsapp_mensagem_id')->constrained('whatsapp_mensagens')->restrictOnDelete();
            $table->boolean('todos_contatos')->default(false);
            $table->string('filtro', 30)->default('sem_filtro');
            $table->dateTime('agendado_para')->nullable();
            $table->string('status', 20)->default('agendado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disparos');
    }
};
