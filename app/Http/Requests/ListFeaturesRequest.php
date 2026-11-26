<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ListFeaturesRequest extends FormRequest
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
            'filter' => ['sometimes', 'array'],
            'filter.group' => ['sometimes', 'nullable', 'string', 'max:100'],
            'filter.category' => ['sometimes', 'nullable', 'string', 'max:100'],
        ];
    }

    public function group(): ?string
    {
        $group = $this->input('filter.group');

        return is_string($group) && $group !== '' ? $group : null;
    }

    public function category(): ?string
    {
        $category = $this->input('filter.category');

        return is_string($category) && $category !== '' ? $category : null;
    }
}
