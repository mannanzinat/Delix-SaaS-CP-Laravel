<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * qr-download
     */
    public function up()
    {
        if (!Schema::hasTable('chat_widget_contacts')) {
            Schema::create('chat_widget_contacts', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150);
                $table->string('username', 100)->unique()->nullable();
                $table->string('unique_id')->unique();
                $table->text('images')->nullable();
                $table->string('phone', 50);
                $table->string('label', 100);
                $table->integer('priority')->nullable(); // Remove auto_increment primary key
                $table->string('welcome_message')->nullable();
                $table->time('available_from')->nullable();
                $table->time('available_to')->nullable();
                $table->string('timezone', 100)->nullable();
                $table->unsignedBigInteger('widget_id')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->boolean('status')->default(1);
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
        Schema::dropIfExists('chat_widget_contacts');
    }
};
