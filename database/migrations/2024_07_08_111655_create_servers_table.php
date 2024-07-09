<?php

use App\Models\Server;
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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->enum('provider', ['aws', 'vultr', 'digitalocean', 'alphanet']);
            $table->string('ip')->nullable();
            $table->string('user_name')->nullable();
            $table->string('password');
            $table->tinyInteger('status')->default(1)->comment('0 inactive, 1 active');
            $table->tinyInteger('default')->default(0);
            $table->timestamps();
        });
        $data = [
            'provider' =>'digitalocean',
            'ip' =>'178.128.107.213',
            'user_name' =>'root',
            'password' => 'manarA@2050a',
            'default' => 1
        ];
        Server::insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
};
