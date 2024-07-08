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
        Schema::create('bot_replie_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bot_reply_id')->nullable();
            $table->foreign('bot_reply_id')->references('id')->on('bot_replies')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('template_id')->nullable();
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade')->onUpdate('set null');
            $table->string('header_text')->nullable()->default('');
            $table->string('footer_text')->nullable()->default('');
            $table->string('header_image')->nullable()->default('');
            $table->string('header_audio')->nullable();
            $table->string('header_video')->nullable()->default('');
            $table->string('header_location')->nullable()->default('');
            $table->string('header_document')->nullable()->default('');
            $table->text('components');
            $table->text('buttons')->nullable();
            $table->text('component_header')->nullable();
            $table->text('component_body')->nullable();
            $table->text('component_buttons')->nullable();
            $table->string('message_type')->default('text'); 
            $table->boolean('status')->default(1);
            $table->enum('source',[TypeEnum::WHATSAPP->value,
                    TypeEnum::TELEGRAM->value,  
            ])->default(TypeEnum::WHATSAPP->value);
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
        Schema::dropIfExists('bot_replie_templates');
    }
};
