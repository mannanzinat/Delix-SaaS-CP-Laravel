<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
class CreateAfterCampaignTableUpdateTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */ 
    public function up()
    {
    //     DB::unprepared('
    //     SET GLOBAL log_bin_trust_function_creators = 1;
    //     CREATE TRIGGER update_campaign_totals  AFTER UPDATE ON messages FOR EACH ROW
    //     BEGIN
    //         IF NEW.status = "delivered" THEN
    //             UPDATE campaigns
    //             SET total_delivered = total_delivered + 1
    //             WHERE id = NEW.campaign_id;
    //         END IF;
    //         IF NEW.status = "sent" THEN
    //             UPDATE campaigns
    //             SET total_sent = total_sent + 1
    //             WHERE id = NEW.campaign_id;
    //         END IF;
    //         IF NEW.status = "read" THEN
    //             UPDATE campaigns
    //             SET total_read = total_read + 1
    //             WHERE id = NEW.campaign_id;
    //         END IF;
    //         IF NEW.status = "failed" THEN
    //         UPDATE campaigns
    //         SET total_failed = total_failed + 1
    //         WHERE id = NEW.campaign_id;
    //     END IF;
    //     END
    // ');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

     
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_campaign_totals');
    }
}