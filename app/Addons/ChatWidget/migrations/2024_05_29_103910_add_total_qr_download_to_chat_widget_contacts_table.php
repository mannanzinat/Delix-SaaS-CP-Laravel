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
        if (!Schema::hasTable('chat_widget_contacts')) {
            Schema::table('chat_widget_contacts', function (Blueprint $table) {
                $table->bigInteger('total_qr_download')->default(0)->after('status');
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
        Schema::table('chat_widget_contacts', function (Blueprint $table) {
            $table->dropColumn('total_qr_download');
        });
    }
};
