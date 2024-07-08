<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {

        // Alter the column to a temporary type (e.g., VARCHAR)
        Schema::table('campaigns', function ($table) {
            DB::statement("ALTER TABLE campaigns MODIFY COLUMN status VARCHAR(255)");
        });
        // Update the existing data with the new enum values
        DB::table('campaigns')
            ->whereNull('status')
            ->update(['status' => 'active']);
            
        DB::table('campaigns')
            ->where('status', 'complete')
            ->update(['status' => 'executed']);
        
        // Alter the column back to the enum type with the updated values
        Schema::table('campaigns', function ($table) {
            DB::statement("ALTER TABLE campaigns MODIFY COLUMN status ENUM('active', 'inactive', 'canceled', 'complete', 'hold', 'queued', 'stopped','processed','executed') NOT NULL DEFAULT 'active'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert the column alteration to the original enum type
        Schema::table('campaigns', function ($table) {
            DB::statement("ALTER TABLE campaigns MODIFY COLUMN status ENUM('active', 'inactive', 'canceled', 'complete', 'hold') NOT NULL DEFAULT 'active'");
        });
    }
};
