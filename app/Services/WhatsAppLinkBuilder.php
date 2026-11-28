<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\NewContactRequestData;
use App\Settings\ContactSettings;

/**
 * Builds the wa.me link the frontend opens after the contact form is submitted.
 */
final class WhatsAppLinkBuilder
{
    public function __construct(private readonly ContactSettings $settings) {}

    public function forContactRequest(NewContactRequestData $data): ?string
    {
        $number = preg_replace('/\D+/', '', $this->settings->whatsapp_number) ?? '';

        if ($number === '') {
            return null;
        }

        $text = strtr($this->settings->whatsapp_message_template, [
            '{name}' => $data->name,
            '{phone}' => $data->phone,
            '{message}' => $data->message ?? '',
        ]);

        // An empty {message} must not leave blank lines at the end.
        $text = trim(preg_replace("/\n{3,}/", "\n\n", $text) ?? $text);

        return 'https://wa.me/' . $number . '?text=' . rawurlencode($text);
    }
}
