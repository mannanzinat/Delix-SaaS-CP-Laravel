<?php

use App\Enums\BotReplyType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->enum('reply_type', [
                BotReplyType::CANNED_RESPONSE->value,
                BotReplyType::EXACT_MATCH->value,
                BotReplyType::CONTAINS->value,
            ])->default(BotReplyType::CONTAINS->value);
            $table->tinyInteger('reply_using_ai')->default('0')->nullable();
            $table->text('reply_text')->nullable();
            $table->text('keywords')->nullable();
            $table->timestamps();
        });
    }

    // BotReplyType
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bot_replies');
    }
};
