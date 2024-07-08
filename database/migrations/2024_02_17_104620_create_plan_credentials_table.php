<?php

use App\Models\PlanCredential;
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
        Schema::create('plan_credentials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->string('title');
            $table->string('value');
            $table->timestamps();
        });

        PlanCredential::create([
            'plan_id' => 2,
            'title'   => 'stripe',
            'value'   => 'price_1OmLoXJUxq2pPEOHfvBIVgmt',
        ]);

        PlanCredential::create([
            'plan_id' => 3,
            'title'   => 'stripe',
            'value'   => 'price_1Ol70AJUxq2pPEOH9iYo69nr',
        ]);
        PlanCredential::create([
            'plan_id' => 5,
            'title'   => 'stripe',
            'value'   => 'price_1OmLq4JUxq2pPEOHJiIwCcTP',
        ]);
        PlanCredential::create([
            'plan_id' => 6,
            'title'   => 'stripe',
            'value'   => 'price_1OmK7ZJUxq2pPEOHiAgV690p',
        ]);
        PlanCredential::create([
            'plan_id' => 6,
            'title'   => 'paddle',
            'value'   => 'pri_01hq5k3xh0qag89kq0fd6x0yyg',
        ]);
        PlanCredential::create([
            'plan_id' => 6,
            'title'   => 'paypal',
            'value'   => 'P-3D044458N8455713HMWGAJII',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_credentials');
    }
};
