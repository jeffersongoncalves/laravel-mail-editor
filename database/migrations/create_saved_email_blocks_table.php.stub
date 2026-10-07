<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_email_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('type', 30)->index();
            $table->json('props');
            $table->unsignedBigInteger('user_id')->nullable(); // No FK constraint — plugin cannot assume host users table
            $table->boolean('is_global')->default(false);
            $table->string('category')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'is_global']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_email_blocks');
    }
};
