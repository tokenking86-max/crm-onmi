<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'number',
        'opportunity_id',
        'client_id',
        'status',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total',
        'valid_until',
        'notes',
        'terms',
        'created_by',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'valid_until' => 'date',
    ];

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    public static function generateNumber(): string
    {
        $year = date('Y');
        $lastQuote = self::where('number', 'like', "COT-{$year}-%")
            ->orderByDesc('number')
            ->first();

        if ($lastQuote) {
            $lastNumber = (int) substr($lastQuote->number, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('COT-%s-%03d', $year, $newNumber);
    }

    public function recalculate(): void
    {
        $subtotal = $this->items->sum('total');
        $taxAmount = $subtotal * ($this->tax_rate / 100);

        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total' => $subtotal + $taxAmount,
        ]);
    }
}
