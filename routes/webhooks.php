<?php

use App\Http\Controllers\GreatpagesWebhookController;
use App\Http\Controllers\HotmartWebhookController;
use Illuminate\Support\Facades\Route;

/*
| Webhooks: chamadas das plataformas de pagamento (Hotmart, Greatpages...).
| Rodam no grupo "api" (sem sessão e sem CSRF), registrado em bootstrap/app.php.
| A segurança de cada webhook é o token da plataforma, conferido no controller.
*/

Route::post('/webhooks/hotmart/eventos/{evento:uuid}', [HotmartWebhookController::class, 'store'])->name('webhooks.hotmart');
Route::post('/webhooks/greatpages/eventos/{evento:uuid}', [GreatpagesWebhookController::class, 'store'])->name('webhooks.greatpages');
