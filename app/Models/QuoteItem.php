<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'description',
        'quantity',
        'unit_price',
        'discount',
        'total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function calculateTotal(): float
    {
        $lineTotal = $this->quantity * $this->unit_price;
        $discountAmount = $lineTotal * ($this->discount / 100);

        return $lineTotal - $discountAmount;
    }

    protected static function booted(): void
    {
        static::saving(function (QuoteItem $item) {
            $item->total = $item->calculateTotal();
        });

        static::saved(function (QuoteItem $item) {
            $item->quote->recalculate();
        });
    }
}
