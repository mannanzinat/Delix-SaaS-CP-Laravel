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
        Schema::table('client_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('client_settings', 'scopes')) {
                $table->json('scopes')->nullable()->after('webhook_verified')->comment('Permissions');
            }
            if (!Schema::hasColumn('client_settings', 'granular_scopes')) {
                $table->json('granular_scopes')->nullable()->after('scopes');
            }
            // if (!Schema::hasColumn('client_settings', 'application_name')) {
            //     $table->string('application_name')->nullable()->after('granular_scopes')->comment('Application name');
            // }
            if (!Schema::hasColumn('client_settings', 'data_access_expires_at')) {
                $table->datetime('data_access_expires_at')->nullable()->after('granular_scopes');
            }
            if (!Schema::hasColumn('client_settings', 'expires_at')) {
                $table->datetime('expires_at')->nullable()->after('data_access_expires_at');
            }
            if (!Schema::hasColumn('client_settings', 'fb_user_id')) {
                $table->string('fb_user_id')->nullable()->after('expires_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_settings', function (Blueprint $table) {
            $table->dropColumn('scopes');
            $table->dropColumn('granular_scopes');
            // $table->dropColumn('application_name');
            $table->dropColumn('data_access_expires_at');
            $table->dropColumn('expires_at');
            $table->dropColumn('fb_user_id');
        });
    }
};
