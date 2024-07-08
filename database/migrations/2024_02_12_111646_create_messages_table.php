<?php

use App\Enums\TypeEnum;
use Illuminate\Support\Facades\DB;
use App\Enums\MessageStatusEnum;
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
        if (!Schema::hasTable('messages')) {

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('message_id',150)->comment('message id from API');
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('conversation_id')->nullable();
            $table->unsignedBigInteger('group_subscriber_id')->nullable();
            $table->string('contacts')->nullable()->default('');
            $table->string('header_text')->nullable()->default('');
            $table->string('footer_text')->nullable()->default('');
            $table->string('header_image')->nullable()->default('');
            $table->string('header_audio')->nullable();
            $table->string('header_video')->nullable()->default('');
            $table->string('header_location')->nullable()->default('');
            $table->string('header_document')->nullable()->default('');
            $table->text('buttons')->nullable();
            $table->text('value')->nullable(); 
            $table->text('component_header')->nullable();
            $table->text('component_body')->nullable();
            $table->text('component_buttons')->nullable();
            $table->string('error')->nullable();
            $table->string('message_type')->default('text');
            $table->enum('status',[MessageStatusEnum::SCHEDULED->value,
                    MessageStatusEnum::SENDING->value,  
                    MessageStatusEnum::SENT->value,
                    MessageStatusEnum::DELIVERED->value,
                    MessageStatusEnum::READ->value,
                    MessageStatusEnum::FAILED->value,
            ])->default(MessageStatusEnum::SENDING->value);
            $table->enum('source',[TypeEnum::WHATSAPP->value,
                    TypeEnum::TELEGRAM->value,  
            ])->default(TypeEnum::WHATSAPP->value);
            $table->timestamp('schedule_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->text('components')->nullable();
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->boolean('is_contact_msg')->default(0);
            $table->boolean('is_campaign_msg')->default(0);
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('set null');
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('set null');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('set null');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
