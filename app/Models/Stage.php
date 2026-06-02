<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
        'color',
        'is_won',
        'is_lost',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_won' => 'boolean',
        'is_lost' => 'boolean',
    ];

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
