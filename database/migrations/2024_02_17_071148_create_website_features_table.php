<?php

use App\Models\WebsiteFeature;
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
        Schema::create('website_features', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('image');
            $table->longText('description');
            $table->string('type');
            $table->enum('status', [0, 1])->default(1);
            $table->timestamps();
        });

        WebsiteFeature::create([
            'type'        => 'whatsapp',
            'title'       => 'Broadcast',
            'description' => '[\"Launch your marketing campaigns and connect with your audience through WhatsApp messages that give high engagement rates of up to 98%.\\r\",\"Organize your contacts by tagging them, grouping them into specific categories, and reaching out with customized messages designed to appeal to each group. you can also schedule the sending time according to your list category.\\r\",\"Send unlimited bulk WhatsApp message notifications to contact listsen\"]',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('website_features');
    }
};
