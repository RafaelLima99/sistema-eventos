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
        Schema::create('automacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->cascadeOnDelete();
            $table->foreignId('whatsapp_mensagem_id')->nullable()->constrained('whatsapp_mensagens')->restrictOnDelete();
            $table->foreignId('email_mensagem_id')->nullable()->constrained('email_mensagens')->restrictOnDelete();
            $table->string('titulo', 255);
            $table->string('canal', 20);
            $table->string('tipo_gatilho', 30)->default('apos_inscricao');
            $table->unsignedInteger('minutos_disparo')->default(0);
            $table->string('filtro', 30)->default('sem_filtro');
            $table->string('status', 20)->default('ativo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('automacoes');
    }
};
