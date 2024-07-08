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
        Schema::create('contact_relation_segments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('segment_id')->nullable();
            $table->foreign('segment_id')->references('id')->on('segments')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('contact_relation_segments');
    }
};
