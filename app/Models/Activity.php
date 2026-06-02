<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'type',
        'activityable_type',
        'activityable_id',
        'due_date',
        'is_completed',
        'description',
        'user_id',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'is_completed' => 'boolean',
    ];

    public function activityable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markCompleted(): void
    {
        $this->update(['is_completed' => true]);
    }
}
