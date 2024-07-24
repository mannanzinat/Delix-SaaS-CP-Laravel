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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id')->unsigned()->nullable();
            $table->bigInteger('server_id')->unsigned()->nullable();
            $table->string('sub_domain')->nullable();
            $table->string('sub_domain_user')->nullable();
            $table->string('sub_domain_password')->nullable();
            $table->string('sub_domain_db_name')->nullable();
            $table->string('sub_domain_db_user')->nullable();
            $table->string('sub_domain_db_password')->nullable();
            $table->string('custom_domain')->nullable();
            $table->string('custom_domain_user')->nullable();
            $table->string('custom_domain_password')->nullable();
            $table->string('custom_domain_db_name')->nullable();
            $table->string('custom_domain_db_user')->nullable();
            $table->string('custom_domain_db_password')->nullable();
            $table->string('admin_key')->nullable();
            $table->string('client_key')->nullable();
            $table->tinyInteger('custom_domain_active')->default(0)->comment('0 inactive, 1 active');
            $table->string('database_name')->nullable();
            $table->string('database_password')->nullable();
            $table->string('site_name')->nullable();
            $table->string('site_password')->nullable();
            $table->tinyInteger('ssl_active')->default(0)->comment('0 inactive, 1 active');
            $table->tinyInteger('dns_active')->default(0)->comment('0 inactive, 1 active');
            $table->tinyInteger('script_deployed')->default(0)->comment('0 inactive, 1 active');
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
        Schema::dropIfExists('domains');
    }
};
