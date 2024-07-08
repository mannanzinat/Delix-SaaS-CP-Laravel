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
        if (!Schema::hasColumn('bot_replies', 'status')) {
            Schema::table('bot_replies', function (Blueprint $table) {
                $table->boolean('status')->default(1)->after('keywords');
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
        Schema::table('bot_replies', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
