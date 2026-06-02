<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->enum('type', ['call', 'email', 'meeting', 'task', 'note', 'whatsapp'])->default('note');
            $table->nullableMorphs('activityable'); // lead, client, opportunity
            $table->datetime('due_date')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
