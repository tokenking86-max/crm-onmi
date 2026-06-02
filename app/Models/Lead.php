<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'source',
        'status',
        'estimated_value',
        'notes',
        'assigned_to',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
    ];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'activityable');
    }

    public function convertToClient(): Client
    {
        $client = Client::create([
            'lead_id' => $this->id,
            'name' => $this->name,
            'company' => $this->company,
            'email' => $this->email,
            'phone' => $this->phone,
            'assigned_to' => $this->assigned_to,
        ]);

        $this->update(['status' => 'qualified']);

        return $client;
    }
}
