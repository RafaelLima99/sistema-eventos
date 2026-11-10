<?php

namespace App\Http\Controllers;

use App\Http\Requests\GreatpagesWebhookRequest;
use App\Models\Evento;
use App\Services\GreatpagesWebhookService;
use Illuminate\Http\JsonResponse;

class GreatpagesWebhookController extends Controller
{
    /**
     * Recebe o formulário da Greatpages. O evento vem do UUID na URL do webhook.
     */
    public function store(GreatpagesWebhookRequest $request, Evento $evento, GreatpagesWebhookService $greatpagesWebhookService): JsonResponse
    {
        // A Greatpages envia o token (campo "Token" do webhook) no header Authorization.
        $tokenConfigurado = config('services.greatpages.token');
        $tokenRecebido = (string) $request->header('Authorization');

        if (blank($tokenRecebido)) {
            abort(401, 'Token não informado no header Authorization.');
        }

        // Recusa se não houver token no .env ou se o token recebido for diferente.
        // hash_equals compara sempre no mesmo tempo, para o token não ser descoberto pelo tempo de resposta.
        if (blank($tokenConfigurado) || ! hash_equals($tokenConfigurado, $tokenRecebido)) {
            abort(403, 'Token inválido.');
        }

        $greatpagesWebhookService->registrarInscricao($evento, $request->validated());

        return response()->json(['status' => 'ok']);
    }
}
