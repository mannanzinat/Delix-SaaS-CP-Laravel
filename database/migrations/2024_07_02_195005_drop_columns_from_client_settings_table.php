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
            // Drop columns if they exist
            if (Schema::hasColumn('client_settings', 'can_join_groups')) {
                $table->dropColumn('can_join_groups');
            }
            if (Schema::hasColumn('client_settings', 'can_read_all_group_messages')) {
                $table->dropColumn('can_read_all_group_messages');
            }
            if (Schema::hasColumn('client_settings', 'supports_inline_queries')) {
                $table->dropColumn('supports_inline_queries');
            }
            if (Schema::hasColumn('client_settings', 'open_ai_key')) {
                $table->dropColumn('open_ai_key');
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
            // Define the columns to add back if needed
            $table->boolean('can_join_groups')->default(false)->after('webhook_verified')->comment('Permission to join groups');
            $table->boolean('can_read_all_group_messages')->default(false)->after('can_join_groups')->comment('Permission to read all group messages');
            $table->boolean('supports_inline_queries')->default(false)->after('can_read_all_group_messages')->comment('Support for inline queries');
            $table->string('open_ai_key')->default(false)->after('token_verified');
        });
    }
};
