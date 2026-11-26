<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContentStatus;
use App\Observers\PartnerObserver;
use App\Traits\HasPublishStatus;
use App\Traits\HasSortOrder;
use Database\Factories\PartnerFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[ObservedBy([PartnerObserver::class])]
class Partner extends Model implements HasMedia
{
    /** @use HasFactory<PartnerFactory> */
    use HasFactory;
    use HasPublishStatus;
    use HasSortOrder;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'url',
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile()
            ->acceptsMimeTypes(['image/png', 'image/jpeg', 'image/webp']);
    }
}
