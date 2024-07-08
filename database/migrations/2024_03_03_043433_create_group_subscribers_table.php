<?php

use App\Enums\TypeEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->nullable();
            $table->string('avatar')->nullable();
            $table->string('phone', 50);
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('cascade');
            $table->string('group_chat_id')->nullable()->comment('for telegram');
            $table->string('group_subscriber_id')->unique()->nullable()->comment('for telegram');
            $table->unsignedBigInteger('group_id')->nullable()->comment('for telegram');
            $table->boolean('is_left_group')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->enum('type', [
                TypeEnum::WHATSAPP->value,
                TypeEnum::TELEGRAM->value,
            ])->default(TypeEnum::TELEGRAM->value);
            $table->boolean('status')->default(1);
            $table->string('is_blacklist')->default(0);
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
        Schema::dropIfExists('group_subscribers');
    }
};
