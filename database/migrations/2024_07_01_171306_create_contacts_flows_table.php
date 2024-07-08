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
        if (!Schema::hasTable('contacts_flows')) {
        Schema::create('contacts_flows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->unsignedBigInteger('flow_id');
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->timestamps(); 
            $table->softDeletes();
            // $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            // $table->foreign('flow_id')->references('id')->on('flows')->onDelete('cascade');
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
        Schema::dropIfExists('contacts_flows');
    }
};
