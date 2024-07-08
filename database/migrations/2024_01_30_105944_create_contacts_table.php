<?php

use App\Enums\TypeEnum;
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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->nullable();
            $table->string('avatar')->nullable();
            $table->string('phone', 50);
            $table->text('images')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('cascade');
            // $table->string('group_chat_id', 11)->nullable()->comment('for telegram group chat id')->unique();
            $table->string('group_chat_id', 11)->unique()->nullable()->comment('for telegram group chat id');
            // $table->string('group_subscriber_id',11)->nullable()->comment('for telegram');
            $table->unsignedBigInteger('group_id')->nullable()->comment('for telegram');
            // $table->foreign('group_id')->references('id')->on('bot_groups')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->enum('type', [
                TypeEnum::WHATSAPP->value,
                TypeEnum::TELEGRAM->value,
            ])->default(TypeEnum::WHATSAPP->value);
            $table->boolean('status')->default(1);
            $table->string('is_blacklist')->default(0);
            $table->boolean('is_verified')->default(0)->comment('Is Verified WhatsApp Number.');
            $table->dateTime('last_conversation_at')->nullable();
            $table->boolean('has_conversation')->default(0);
            $table->boolean('has_unread_conversation')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};
