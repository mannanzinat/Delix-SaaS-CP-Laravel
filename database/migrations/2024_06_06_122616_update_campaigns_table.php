<?php

use App\Models\Contact;
use App\Models\BotGroup;
use App\Models\Campaign;
use App\Models\ClientSetting;
use App\Models\GroupSubscriber;
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
        // Add new columns if they don't exist
        Schema::table('campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('campaigns', 'contact_list_ids')) {
                $table->json('contact_list_ids')->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'segment_ids')) {
                $table->json('segment_ids')->nullable();
            }
        });

        // Populate contact_list_ids
        // if (Schema::hasColumn('campaigns', 'contact_list_id')) {
        //     Campaign::whereNotNull('contact_list_id')->orderBy('id')->chunk(100, function ($campaigns) {
        //         foreach ($campaigns as $campaign) {
        //             $contactListIds = [$campaign->contact_list_id];
        //             DB::table('campaigns')
        //                 ->where('id', $campaign->id)
        //                 ->update([
        //                     'contact_list_ids' => $contactListIds,
        //                 ]);
        //         }
        //     });
        // }

        // Populate segment_ids
        // if (Schema::hasColumn('campaigns', 'segment_id')) {
        //     Campaign::orderBy('id')->whereNotNull('segment_id')->chunk(100, function ($campaigns) {
        //         foreach ($campaigns as $campaign) {
        //             $segmentIds = [$campaign->segment_id];
        //             DB::table('campaigns')
        //                 ->where('id', $campaign->id)
        //                 ->update([
        //                     'segment_ids' => $segmentIds,
        //                 ]);
        //         }
        //     });
        // }
        // Contact::withTrashed()->whereNotNull('deleted_at')->forceDelete();
        // BotGroup::withTrashed()->whereNotNull('deleted_at')->forceDelete();
        // GroupSubscriber::withTrashed()->whereNotNull('deleted_at')->forceDelete();
        // ClientSetting::withTrashed()->whereNotNull('deleted_at')->forceDelete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            if (Schema::hasColumn('campaigns', 'contact_list_ids')) {
                $table->dropColumn('contact_list_ids');
            }
            if (Schema::hasColumn('campaigns', 'segment_ids')) {
                $table->dropColumn('segment_ids');
            }
        });
    }
};
