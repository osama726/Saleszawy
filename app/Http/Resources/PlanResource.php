<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Plan
 */
final class PlanResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => [
                'monthly' => $this->money($this->price_monthly),
                'yearly' => $this->money($this->price_yearly),
                'currency' => $this->currency,
            ],
            'trial_days' => $this->trial_days,
            'limits' => [
                'sales_users' => $this->max_sales_users,
                'leads' => $this->max_leads,
            ],
            'features' => $this->features ?? [],
            'is_featured' => $this->is_featured,
            'badge' => $this->badge,
            'cta' => [
                'label' => $this->cta_label,
                'url' => $this->cta_url,
            ],
        ];
    }

    /**
     * 6500.00 -> 6500 (int) so the JSON has clean numbers.
     */
    private function money(mixed $value): int|float|null
    {
        if ($value === null) {
            return null;
        }

        $number = (float) $value;

        return floor($number) === $number ? (int) $number : $number;
    }
}
