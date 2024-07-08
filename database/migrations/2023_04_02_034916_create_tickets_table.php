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
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('client');
            $table->string('client_staff');
            $table->string('subject');
            $table->unsignedBigInteger('department_id');
            $table->string('priority');
            $table->string('status');
            $table->string('ticket_id')->nullable();
            $table->boolean('viewed')->default(false);
            $table->boolean('client_viewed')->default(false);
            $table->text('images')->nullable();
            $table->text('body')->nullable();
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
        Schema::dropIfExists('tickets');
    }
};
