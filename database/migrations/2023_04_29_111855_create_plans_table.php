<?php

use App\Models\Plan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->double('price')->default(0.00);
            $table->string('billing_period')->nullable();
            $table->integer('active_merchant')->nullable();
            $table->integer('monthly_parcel')->nullable();
            $table->integer('active_rider')->nullable();
            $table->boolean('is_free')->default(0)->comment('1=yes, 0=no');
            $table->boolean('is_feature')->default(0)->comment('1=yes, 0=no');
            $table->integer('active_staff')->nullable();
            $table->boolean('custom_domain')->default(0)->comment('1=yes, 0=no');
            $table->boolean('branded_website')->default(0)->comment('1=yes, 0=no');
            $table->boolean('white_level')->default(0)->comment('1=yes, 0=no');
            $table->boolean('rider_app')->default(0)->comment('1=yes, 0=no');
            $table->boolean('merchant_app')->default(0)->comment('1=yes, 0=no');
            $table->string('color')->default('#E0E8F9');
            $table->tinyInteger('status')->default(1)->comment('0 inactive, 1 active');
            $table->timestamps();
        });


        Plan::create([
            'name'               => 'Advance',
            'description'        => '',
            'price'              => 2,
            'billing_period'     => 'monthly',
            'active_merchant'    => 1000,
            'monthly_parcel'     => 10,
            'active_rider'       => 4000,
            'active_staff'       => 0,
            'custom_domain'      => 1,
            'branded_website'    => 1,
            'white_level'        => 1,
            'rider_app'          => 1,
            'merchant_app'       => 1,
            'color'              => '#E0E8F9',
            'is_free'            => 0,
            'status'             => 1,
        ]);

        Plan::create([
            'name'               => 'Enterprise',
            'description'        => '',
            'price'              => 2,
            'billing_period'     => 'monthly',
            'active_merchant'    => 1000,
            'monthly_parcel'     => 10,
            'active_rider'       => 4000,
            'active_staff'       => 0,
            'custom_domain'      => 1,
            'branded_website'    => 1,
            'white_level'        => 1,
            'rider_app'          => 1,
            'merchant_app'       => 1,
            'color'              => '#E0E8F9',
            'is_free'            => 0,
            'status'             => 1,
        ]);

        Plan::create([
            'name'               => 'Enterprise',
            'description'        => '',
            'price'              => 2,
            'billing_period'     => 'monthly',
            'active_merchant'    => 1000,
            'monthly_parcel'     => 10,
            'active_rider'       => 4000,
            'active_staff'       => 0,
            'custom_domain'      => 1,
            'branded_website'    => 1,
            'white_level'        => 1,
            'rider_app'          => 1,
            'merchant_app'       => 1,
            'color'              => '#E0E8F9',
            'is_free'            => 0,
            'status'             => 1,
        ]);

        Plan::create([
            'name'               => 'Enterprise',
            'description'        => '',
            'price'              => 2,
            'billing_period'     => 'monthly',
            'active_merchant'    => 1000,
            'monthly_parcel'     => 10,
            'active_rider'       => 4000,
            'active_staff'       => 0,
            'custom_domain'      => 1,
            'branded_website'    => 1,
            'white_level'        => 1,
            'rider_app'          => 1,
            'merchant_app'       => 1,
            'color'              => '#E0E8F9',
            'is_free'            => 0,
            'status'             => 1,
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('plans');
    }
};
