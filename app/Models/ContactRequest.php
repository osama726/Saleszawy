<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContactStatus;
use Database\Factories\ContactRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactRequest extends Model
{
    /** @use HasFactory<ContactRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'message',
        'source',
        'status',
        'assigned_to',
        'notes',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'status' => ContactStatus::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
