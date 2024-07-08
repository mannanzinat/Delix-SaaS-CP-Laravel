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
        Schema::table('flows', function (Blueprint $table) {
            $table->json('contact_list_ids')->nullable()->after('name');
            $table->json('segment_ids')->nullable()->after('contact_list_ids');
            $table->enum('flow_for', ['whatsapp', 'telegram','fb','instagram'])->default('whatsapp')->after('contact_list_ids');
            $table->enum('flow_type', ['generic', 'campaign'])->default('generic')->after('flow_for');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flows', function (Blueprint $table) {
            $table->dropColumn('contact_list_ids');
            $table->dropColumn('segment_ids');
            $table->dropColumn('flow_for');
            $table->dropColumn('flow_type');
        });
    }
};
