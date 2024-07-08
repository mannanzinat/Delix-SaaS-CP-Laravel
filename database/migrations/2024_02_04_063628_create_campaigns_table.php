<?php

use App\Enums\TypeEnum;
use App\Enums\StatusEnum;
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
        if (!Schema::hasTable('campaigns')) {
            Schema::create('campaigns', function (Blueprint $table) {
                $table->id();
                $table->string('campaign_name')->nullable();
                $table->integer('total_contact')->default(0);
                $table->integer('total_sent')->default(0);
                $table->integer('total_delivered')->default(0);
                $table->integer('total_read')->default(0);
                $table->integer('total_failed')->default(0);
                $table->string('media_url', 255)->nullable();
                $table->string('url_link')->nullable();
                $table->enum('campaign_type', [
                    TypeEnum::WHATSAPP->value, 
                    TypeEnum::TELEGRAM->value,
                ])->default(TypeEnum::WHATSAPP->value);
                $table->enum('status', [
                    StatusEnum::ACTIVE->value,
                    StatusEnum::INACTIVE->value,
                    StatusEnum::CANCELED->value,
                    StatusEnum::COMPLETE->value,
                    StatusEnum::HOLD->value,
                ])->default(StatusEnum::ACTIVE->value);
                $table->unsignedBigInteger('template_id')->nullable();
                $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade')->onUpdate('set null');
                $table->unsignedBigInteger('contact_list_id')->nullable();
                $table->foreign('contact_list_id')->references('id')->on('contacts_lists')->onDelete('cascade')->onUpdate('cascade');
                $table->unsignedBigInteger('segment_id')->nullable();
                $table->foreign('segment_id')->references('id')->on('segments')->onDelete('cascade')->onUpdate('set null');
                $table->unsignedBigInteger('client_id')->nullable();
                $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('cascade');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
                $table->softDeletes();
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
        Schema::dropIfExists('campaigns');
    }
};
