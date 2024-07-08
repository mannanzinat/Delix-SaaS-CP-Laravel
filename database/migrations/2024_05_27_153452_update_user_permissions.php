<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $primaryUsers = DB::table('users')->where('is_primary', 1)->where('user_type', '=','manage_template')->get();
        foreach ($primaryUsers as $user) {
            $permissions = ['manage_whatsapp', 'manage_telegram', 'manage_ai_writer', 'manage_team', 'manage_chat', 'manage_campaigns', 'manage_ticket', 'manage_setting','manage_widget','manage_template','manage_flow'];
            $userPermissions = json_encode($permissions);
            DB::table('users')->where('id', $user->id)->update(['permissions' => $userPermissions]);
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Since this is a data migration, we don't need to rollback the data change.
        // You may implement the rollback logic if necessary.
    }
};
