<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContentStatus;
use App\Observers\FaqObserver;
use App\Traits\HasPublishStatus;
use App\Traits\HasSortOrder;
use Database\Factories\FaqFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([FaqObserver::class])]
class Faq extends Model
{
    /** @use HasFactory<FaqFactory> */
    use HasFactory;
    use HasPublishStatus;
    use HasSortOrder;

    protected $fillable = [
        'group',
        'question',
        'answer',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'status' => ContentStatus::class,
        ];
    }
}
