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
        Schema::table('campaigns', function (Blueprint $table) {
            $table->json('contact_list_ids')->nullable();
            $table->json('segment_ids')->nullable();
        });
          // Convert existing integer data to array and update new columns
        //   DB::table('campaigns')->orderBy('id')->chunk(100, function ($campaigns) {
        //     foreach ($campaigns as $campaign) {
        //         $contactListIds = [$campaign->contact_list_id];
        //         $segmentIds = [$campaign->segment_id];

        //         DB::table('campaigns')
        //             ->where('id', $campaign->id)
        //             ->update([
        //                 'contact_list_ids' => json_encode($contactListIds),
        //                 'segment_ids' => json_encode($segmentIds),
        //             ]);
        //     }
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverting the column types back to unsigned big integer
        Schema::table('campaigns', function (Blueprint $table) {
            $table->unsignedBigInteger('contact_list_id')->nullable()->change();
            $table->unsignedBigInteger('segment_id')->nullable()->change();
        });

        // Re-adding foreign key constraints
        Schema::table('campaigns', function (Blueprint $table) {
            $table->foreign('contact_list_id')->references('id')->on('contacts_lists')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('segment_id')->references('id')->on('segments')->onDelete('cascade')->onUpdate('set null');
        });
    }
    
};
