<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stage_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('probability', 5, 2)->default(0); // 0-100%
            $table->date('expected_close_date')->nullable();
            $table->date('actual_close_date')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('stage_id');
            $table->index('expected_close_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
