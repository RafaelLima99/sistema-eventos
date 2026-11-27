<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Registro de quem já recebeu cada automação: uma linha por automação + inscrito.
     * Evita enviar a mesma mensagem duas vezes para a mesma pessoa.
     */
    public function up(): void
    {
        Schema::create('automacao_envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('automacao_id')->constrained('automacoes')->cascadeOnDelete();
            $table->foreignId('inscrito_id')->constrained('inscritos')->cascadeOnDelete();
            $table->timestamps();

            // O banco recusa a mesma automação duas vezes para o mesmo inscrito.
            $table->unique(['automacao_id', 'inscrito_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('automacao_envios');
    }
};
