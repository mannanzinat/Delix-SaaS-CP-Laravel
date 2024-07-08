<?php

use App\Models\WebsiteStory;
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
        Schema::create('website_stories', function (Blueprint $table) {
            $table->id();
            $table->longText('description');
            $table->text('image');
            $table->enum('status', [0, 1])->default(1);
            $table->timestamps();
        });
        WebsiteStory::create([
            'name' => 'Got 10X sales within a week by reaching out to 10,000 potential clients on WhatsApp and Telegram',
        ]);

        WebsiteStory::create([
            'name' => 'SaleBot bulk sender helped me expand my network on WhatsApp, leading to a significant boost in my subscriber base.',
        ]);
        WebsiteStory::create([
            'name' => 'With SaleBot assistance, I effortlessly sent updates and promotions to my customers on WhatsApp and Telegram, driving 95% engagement and repeat purchases.',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('website_stories');
    }
};
