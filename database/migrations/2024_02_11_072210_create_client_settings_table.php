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
        Schema::create('client_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->text('access_token')->nullable()->comment('whatsapp user access token: https://business.facebook.com/settings/system-users/');
            $table->string('phone_number_id')->nullable()->comment('In the facebook app, in Whatsapp -> API setup, you will find your Phone number ID');
            $table->string('business_account_id', 100)->nullable()->comment('In the facebook app, in Whatsapp -> API setup, you will find your WhatsApp Business Account ID');
            $table->tinyInteger('is_connected')->default(0)->comment('Is WhatsApp Connected: 0 No, 1 Yes');
            $table->enum('type', [
                TypeEnum::WHATSAPP->value,
                TypeEnum::TELEGRAM->value,
            ])->default(TypeEnum::WHATSAPP->value);
            $table->string('bot_id')->nullable()->comment('telegram bot id');
            $table->string('name')->nullable()->comment('telegram bot name');
            $table->string('username')->nullable()->comment('telegram bot username');
            $table->boolean('can_join_groups')->default(0)->nullable()->comment('telegram bot joining group access permission');
            $table->boolean('can_read_all_group_messages')->default(0)->nullable()->comment('telegram bot can_read_all_message');
            $table->boolean('supports_inline_queries')->default(0)->nullable()->comment('telegram bot supports_inline_queries');
            $table->boolean('webhook_verified')->default(0);
            $table->boolean('token_verified')->default(0);
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
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
        Schema::dropIfExists('client_settings');
    }
};
