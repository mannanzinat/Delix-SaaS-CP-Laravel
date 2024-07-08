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
        DB::table('email_templates')
            ->where('identifier', 'password_reset_email')
            ->update(['body' => 'Hi {name},<br>Reset your password using below link:{reset_link}']);
        DB::table('email_templates')
            ->where('identifier', 'recovery_email')
            ->update(['body' => 'Hi {name},
            <p>Your password has been successfully changed.</p>
            <p>If you did not request this change, please contact us immediately.</p>
            <p> Best regards,</p>
            {site_name}']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            //
        });
    }
};
