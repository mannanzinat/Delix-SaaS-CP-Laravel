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
        Schema::table('contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('contacts', 'email')) {
                $table->string('email')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'email')) {
                $table->unique(['email', 'client_id']);
            }
            if (!Schema::hasColumn('contacts', 'address')) {
                $table->string('address')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'state')) {
                $table->string('state')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'zipcode')) {
                $table->string('zipcode')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'email')) {
                $table->date('birthdate')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable();
            }
            if (!Schema::hasColumn('contacts', 'occupation')) {
                $table->string('occupation')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'company')) {
                $table->string('company')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'notes')) {
                $table->text('notes')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'source')) {
                $table->string('source')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'rating')) {
                $table->enum('rating', array_keys(config('static_array.lead_rating')))->nullable();
            }
            if (!Schema::hasColumn('contacts', 'assigned_to')) {
                $table->unsignedBigInteger('assigned_to')->nullable();
                $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
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
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropUnique(['email', 'client_id']);
            $table->dropColumn([
                'email', 'address', 'city', 'state', 'zipcode',
                'birthdate', 'gender', 'occupation', 'company', 'notes',
                'language', 'source', 'rating', 'assigned_to'
            ]);
           
        });
    }
};
