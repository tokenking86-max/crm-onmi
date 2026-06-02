<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lead_id',
        'name',
        'company',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'notes',
        'assigned_to',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'activityable');
    }
}
