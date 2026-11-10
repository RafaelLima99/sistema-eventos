<?php

namespace App\Http\Controllers;

use App\Http\Requests\HotmartWebhookRequest;
use App\Models\Evento;
use App\Services\HotmartWebhookService;
use Illuminate\Http\JsonResponse;

class HotmartWebhookController extends Controller
{
    /**
     * Recebe o webhook da Hotmart. O evento vem do UUID na URL do webhook.
     */
    public function store(HotmartWebhookRequest $request, Evento $evento, HotmartWebhookService $hotmartWebhookService): JsonResponse
    {
        // A Hotmart envia o token (hottok) no header X-HOTMART-HOTTOK.
        $tokenConfigurado = config('services.hotmart.hottok');
        $tokenRecebido = (string) $request->header('X-HOTMART-HOTTOK');

        if (blank($tokenRecebido)) {
            abort(401, 'Token não informado no header X-HOTMART-HOTTOK.');
        }

        // Recusa se não houver token no .env ou se o token recebido for diferente.
        // hash_equals compara sempre no mesmo tempo, para o token não ser descoberto pelo tempo de resposta.
        if (blank($tokenConfigurado) || ! hash_equals($tokenConfigurado, $tokenRecebido)) {
            abort(403, 'Token inválido.');
        }

        // Por enquanto só a compra aprovada gera inscrição. Os outros eventos respondem 200
        // para a Hotmart não ficar reenviando.
        if ($request->input('event') !== 'PURCHASE_APPROVED') {
            return response()->json(['status' => 'ignorado']);
        }

        $hotmartWebhookService->registrarCompraAprovada($evento, $request->validated('data'));

        return response()->json(['status' => 'ok']);
    }
}
