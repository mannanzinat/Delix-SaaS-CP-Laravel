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
        Schema::create('bot_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('group_id')->nullable();
            $table->string('super')->nullable();
            $table->unsignedBigInteger('client_setting_id');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $table->enum('is_admin', ['0', '1'])->default('0');
            $table->enum('type', [
                TypeEnum::WHATSAPP->value,
                TypeEnum::TELEGRAM->value,
            ])->default(TypeEnum::TELEGRAM->value);
            $table->string('supergroup_subscriber_id')->unique()->nullable();           
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
        Schema::dropIfExists('bot_groups');
    }
};
