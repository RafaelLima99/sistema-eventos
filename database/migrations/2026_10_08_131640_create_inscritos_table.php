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
        Schema::create('inscritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->cascadeOnDelete();
            $table->foreignId('contato_id')->constrained('contatos')->cascadeOnDelete();
            $table->string('status_ingresso', 20)->default('pendente');
            $table->string('status_presenca', 20)->default('pendente');
            $table->string('plataforma', 30)->nullable();
            $table->string('tipo_ingresso', 50);
            $table->unsignedSmallInteger('quantidade_ingresso')->default(1);
            $table->decimal('valor_pago', 10, 2)->default(0);
            $table->string('utm_source', 255)->nullable();
            $table->string('utm_medium', 255)->nullable();
            $table->string('utm_campaign', 255)->nullable();
            $table->string('utm_content', 255)->nullable();
            $table->timestamps();

            $table->unique(['evento_id', 'contato_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscritos');
    }
};
