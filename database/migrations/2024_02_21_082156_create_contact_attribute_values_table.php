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
        Schema::create('contact_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->string('attr_value');
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('attribute_id')->nullable();
            $table->foreign('attribute_id')->references('id')->on('contact_attributes')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status', [0, 1,])->default(1);
            $table->softDeletes();
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
        Schema::dropIfExists('contact_attribute_values');
    }
};
