<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('source')->nullable(); // referral, website, cold_call, social_media, event
            $table->enum('status', ['new', 'contacted', 'qualified', 'unqualified'])->default('new');
            $table->decimal('estimated_value', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
