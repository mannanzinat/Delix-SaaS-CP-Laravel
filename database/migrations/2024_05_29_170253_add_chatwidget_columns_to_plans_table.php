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
        Schema::table('plans', function (Blueprint $table) {
            $table->tinyInteger('access_chatwidget')->default(0)->after('telegram_access'); // Adjust the position as needed
            $table->integer('max_chatwidget')->default(0)->after('access_chatwidget');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('access_chatwidget');
            $table->dropColumn('max_chatwidget');
        });
    }
};
