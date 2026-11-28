<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Http\Requests\StoreContactRequest;
use Illuminate\Support\Str;

final readonly class NewContactRequestData
{
    public function __construct(
        public string $name,
        public string $phone,
        public ?string $message,
        public ?string $source,
        public ?string $ipAddress,
        public ?string $userAgent,
        public bool $isBot,
    ) {}

    public static function fromRequest(StoreContactRequest $request): self
    {
        return new self(
            name: $request->string('name')->toString(),
            phone: $request->string('phone')->toString(),
            message: $request->filled('message') ? $request->string('message')->toString() : null,
            source: $request->filled('source') ? $request->string('source')->toString() : null,
            ipAddress: $request->ip(),
            userAgent: $request->userAgent() !== null ? Str::limit($request->userAgent(), 500, '') : null,
            // Honeypot: the hidden "website" field must stay empty for real visitors.
            isBot: $request->filled('website'),
        );
    }
}
