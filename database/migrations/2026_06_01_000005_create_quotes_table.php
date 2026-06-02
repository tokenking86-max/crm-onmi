<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // COT-2026-001
            $table->foreignId('opportunity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['draft', 'sent', 'approved', 'rejected', 'expired'])->default('draft');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(18.00); // IGV Peru
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->date('valid_until')->nullable();
            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
