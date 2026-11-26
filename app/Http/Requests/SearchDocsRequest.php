<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class SearchDocsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'q' => ['required', 'string', 'min:2', 'max:100'],
        ];
    }

    public function term(): string
    {
        return trim((string) $this->input('q'));
    }
}
