<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ListPostsRequest extends FormRequest
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
            'filter.category' => ['sometimes', 'nullable', 'string', 'max:100'],
            'filter.tag' => ['sometimes', 'nullable', 'string', 'max:100'],
            'filter.featured' => ['sometimes', 'nullable', 'boolean'],
            'q' => ['sometimes', 'nullable', 'string', 'max:200'],
            'limit' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:50'],
        ];
    }

    public function category(): ?string
    {
        $category = $this->input('filter.category');

        return is_string($category) && $category !== '' ? $category : null;
    }

    public function tag(): ?string
    {
        $tag = $this->input('filter.tag');

        return is_string($tag) && $tag !== '' ? $tag : null;
    }

    public function featured(): ?bool
    {
        if (! $this->has('filter.featured') || $this->input('filter.featured') === null || $this->input('filter.featured') === '') {
            return null;
        }

        return $this->boolean('filter.featured');
    }

    public function search(): ?string
    {
        $q = $this->input('q');

        return is_string($q) && trim($q) !== '' ? trim($q) : null;
    }

    public function limit(): ?int
    {
        $limit = $this->input('limit');

        return $limit !== null && $limit !== '' ? (int) $limit : null;
    }
}
