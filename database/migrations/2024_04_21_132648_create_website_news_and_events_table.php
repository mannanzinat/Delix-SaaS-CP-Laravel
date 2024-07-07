<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('website_news_and_events', function (Blueprint $table) {
            $table->id();
            $table->text('image')->nullable();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0 inactive, 1 active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_news_and_events');
    }
};
