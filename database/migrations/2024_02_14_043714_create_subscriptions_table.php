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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->string('is_recurring');
            $table->boolean('status')->default(0)->comment('0:pending,1:active,2:rejected,3:inactive');
            $table->date('expire_date')->nullable();
            $table->dateTime('purchase_date')->nullable();
            $table->double('price')->default(0);
            $table->string('package_type');
            $table->unsignedBigInteger('active_merchant')->default(0);
            $table->unsignedBigInteger('monthly_parcel')->default(0);
            $table->unsignedBigInteger('active_rider')->default(0);
            $table->unsignedBigInteger('active_staff')->default(0);
            $table->unsignedBigInteger('custom_domain')->default(0);
            $table->boolean('branded_website')->default(0);
            $table->boolean('white_level')->default(0);
            $table->boolean('merchant_app')->default(0);
            $table->boolean('rider_app')->default(0);
            $table->string('trx_id');
            $table->string('payment_method');
            $table->text('payment_details');
            $table->text('document')->nullable();
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
        Schema::dropIfExists('subscriptions');
    }
};
