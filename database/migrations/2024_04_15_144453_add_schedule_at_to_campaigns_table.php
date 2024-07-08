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
        if (!Schema::hasColumn('campaigns', 'schedule_at')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->timestamp('schedule_at')->nullable()->after('status');
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
        if (!Schema::hasColumn('campaigns', 'schedule_at')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->dropColumn('schedule_at');
            });
        }
    }
};
