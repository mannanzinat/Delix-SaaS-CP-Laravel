<?php

use App\Models\WebsiteStoryLanguage;
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
        Schema::create('website_story_languages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_story_id');
            $table->string('lang');
            $table->longText('description');
            $table->timestamps();
        });
        WebsiteStoryLanguage::create([
            'website_story_id' => '1',
            'lang'             => 'en',
            'description'      => 'Got 10X sales within a week by reaching out to 10,000 potential clients on WhatsApp and Telegram',
        ]);

        WebsiteStoryLanguage::create([
            'website_story_id' => '2',
            'lang'             => 'en',
            'description'      => 'SaleBot bulk sender helped me expand my network on WhatsApp, leading to a significant boost in my subscriber base',
        ]);
        WebsiteStoryLanguage::create([
            'website_story_id' => '3',
            'lang'             => 'en',
            'description'      => 'With SaleBot assistance, I effortlessly sent updates and promotions to my customers on WhatsApp and Telegram, driving 95% engagement and repeat purchases',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('website_story_languages');
    }
};
