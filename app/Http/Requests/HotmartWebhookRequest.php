<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class HotmartWebhookRequest extends FormRequest
{
    /**
     * Só aceita a chamada se o token (hottok) enviado pela Hotmart for o configurado no .env.
     */
    public function authorize(): bool
    {
        $hottok = config('services.hotmart.hottok');

        return filled($hottok) && hash_equals($hottok, (string) $this->header('X-HOTMART-HOTTOK'));
    }

    /**
     * Os dados do comprador só são exigidos na compra aprovada; os outros eventos são ignorados.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event' => ['required', 'string'],
            'data.buyer.name' => ['exclude_unless:event,PURCHASE_APPROVED', 'required', 'string', 'max:255'],
            'data.buyer.email' => ['exclude_unless:event,PURCHASE_APPROVED', 'required', 'email', 'max:255'],
            'data.buyer.document' => ['exclude_unless:event,PURCHASE_APPROVED', 'nullable', 'string'],
            'data.buyer.checkout_phone' => ['exclude_unless:event,PURCHASE_APPROVED', 'nullable', 'string', 'max:20'],
            'data.purchase.price.value' => ['exclude_unless:event,PURCHASE_APPROVED', 'required', 'numeric', 'min:0'],
            'data.purchase.offer.code' => ['exclude_unless:event,PURCHASE_APPROVED', 'nullable', 'string', 'max:50'],
            'data.purchase.event_tickets.amount' => ['exclude_unless:event,PURCHASE_APPROVED', 'nullable', 'integer', 'min:1'],
        ];
    }
}
