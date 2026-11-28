<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Actions\Contact\StoreContactRequestAction;
use App\DTOs\NewContactRequestData;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StoreContactRequest;
use App\Services\WhatsAppLinkBuilder;
use Illuminate\Http\JsonResponse;

final class ContactRequestController extends ApiController
{
    public function store(
        StoreContactRequest $request,
        StoreContactRequestAction $action,
        WhatsAppLinkBuilder $whatsApp,
    ): JsonResponse {
        $data = NewContactRequestData::fromRequest($request);

        $action->execute($data);

        // Same response shape for everyone (bots included), so the honeypot reveals nothing.
        return $this->created(
            ['whatsapp_url' => $whatsApp->forContactRequest($data)],
            'تم استلام طلبك وسنتواصل معك قريباً',
        );
    }
}
