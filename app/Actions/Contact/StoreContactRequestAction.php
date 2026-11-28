<?php

declare(strict_types=1);

namespace App\Actions\Contact;

use App\DTOs\NewContactRequestData;
use App\Enums\ContactStatus;
use App\Models\ContactRequest;

final class StoreContactRequestAction
{
    /**
     * Returns null when the honeypot caught a bot: nothing is stored and the caller still answers
     * "success", so bots learn nothing.
     */
    public function execute(NewContactRequestData $data): ?ContactRequest
    {
        if ($data->isBot) {
            return null;
        }

        return ContactRequest::create([
            'name' => $data->name,
            'phone' => $data->phone,
            'message' => $data->message,
            'source' => $data->source,
            'status' => ContactStatus::New,
            'ip_address' => $data->ipAddress,
            'user_agent' => $data->userAgent,
        ]);
    }
}
