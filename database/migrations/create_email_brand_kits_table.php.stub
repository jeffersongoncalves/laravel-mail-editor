<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_brand_kits', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_default')->default(false);
            $table->string('logo_url')->nullable();
            $table->string('logo_alt')->nullable();
            $table->json('colors');
            $table->json('typography');
            $table->json('social_links')->nullable();
            $table->text('footer_address')->nullable();
            $table->string('unsubscribe_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_brand_kits');
    }
};
