<?php

use App\Services\AutomacaoEnvioService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('queue:prune-failed --hours=168')->daily();
Schedule::command('queue:prune-batches --hours=48 --unfinished=72 --cancelled=72')->daily();

// Envia as mensagens das automações com tempo (ex.: 15 min após a inscrição) para quem já chegou no horário.
// Por enquanto só e-mail; o WhatsApp ainda falta implementar (ver TODO no AutomacaoEnvioService).
// Roda a cada minuto; withoutOverlapping() não deixa começar uma rodada antes de a anterior terminar.
// Roda no container "scheduler" (desenvolvimento e produção), que sobe junto com o docker compose up -d.
Schedule::call(fn (AutomacaoEnvioService $automacaoEnvioService) => $automacaoEnvioService->enviarMensagensAgendadasSeHouver())
    ->name('enviar-mensagens-agendadas-das-automacoes')
    ->everyMinute()
    ->withoutOverlapping();
