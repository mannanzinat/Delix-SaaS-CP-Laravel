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

        if (!Schema::hasColumn('clients', 'timezone')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->string('timezone')->nullable()->after('country_id');
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
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'timezone')) {
                $table->dropColumn('timezone');
            }
        });
    }
};
