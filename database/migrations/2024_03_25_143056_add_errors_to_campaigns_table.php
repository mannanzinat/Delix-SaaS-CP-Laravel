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
        if (!Schema::hasColumn('campaigns', 'errors')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->text('errors')->nullable()->after('status');
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
        Schema::table('campaigns', function (Blueprint $table) {
            if (Schema::hasColumn('campaigns', 'errors')) {
                $table->dropColumn('errors');
            }
        });
    }
};
