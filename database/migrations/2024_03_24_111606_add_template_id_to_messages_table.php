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
        if (!Schema::hasColumn('messages', 'template_id')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->unsignedBigInteger('template_id')->nullable()->after('conversation_id');
                $table->foreign('template_id')
                      ->references('id')
                      ->on('templates')
                      ->onDelete('cascade')
                      ->onUpdate('set null');
            });
        }
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'template_id')) {
                $table->dropForeign(['template_id']);
                $table->dropColumn('template_id');
            }
        });
    }
};
