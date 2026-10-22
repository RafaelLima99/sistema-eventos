<?php

namespace App\Http\Controllers;

use App\Http\Requests\HotmartWebhookRequest;
use App\Models\Evento;
use App\Services\HotmartWebhookService;
use Illuminate\Http\JsonResponse;

class HotmartWebhookController extends Controller
{
    /**
     * Recebe o webhook da Hotmart. O evento vem do UUID no link gerado na página do evento.
     */
    public function store(HotmartWebhookRequest $request, Evento $evento, HotmartWebhookService $hotmartWebhookService): JsonResponse
    {
        // Por enquanto só a compra aprovada gera inscrição. Os outros eventos respondem 200
        // para a Hotmart não ficar reenviando.
        if ($request->input('event') !== 'PURCHASE_APPROVED') {
            return response()->json(['status' => 'ignorado']);
        }

        $hotmartWebhookService->registrarCompraAprovada($evento, $request->validated('data'));

        return response()->json(['status' => 'ok']);
    }
}
