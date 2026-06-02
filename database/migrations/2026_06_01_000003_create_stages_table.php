<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('order');
            $table->string('color')->default('#6366f1');
            $table->boolean('is_won')->default(false);
            $table->boolean('is_lost')->default(false);
            $table->timestamps();

            $table->unique('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stages');
    }
};
