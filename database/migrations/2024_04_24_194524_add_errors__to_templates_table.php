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
        if (!Schema::hasColumn('templates', 'errors')) {
        Schema::table('templates', function (Blueprint $table) {
            $table->text('errors')->nullable(); // Add a nullable text column
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
        
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn('errors'); // Remove the column on rollback
        });
    }
};
