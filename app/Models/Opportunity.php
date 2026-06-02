<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Opportunity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'client_id',
        'stage_id',
        'amount',
        'probability',
        'expected_close_date',
        'actual_close_date',
        'description',
        'assigned_to',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'probability' => 'decimal:2',
        'expected_close_date' => 'date',
        'actual_close_date' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'activityable');
    }

    public function isWon(): bool
    {
        return $this->stage->is_won;
    }

    public function isLost(): bool
    {
        return $this->stage->is_lost;
    }

    public function getWeightedAmountAttribute(): float
    {
        return $this->amount * ($this->probability / 100);
    }
}
