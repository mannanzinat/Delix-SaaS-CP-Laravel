<?php

use App\Models\WebsitePartnerLogo;
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
        Schema::create('website_partner_logos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('image');
            $table->enum('status', [0, 1,])->default(1);
            $table->timestamps();
        });

        WebsitePartnerLogo::create([
            'name'          => 'Google',
        ]);
        WebsitePartnerLogo::create([
            'name'          => 'Facebook',
        ]);
        WebsitePartnerLogo::create([
            'name'          => 'Twitter',
        ]);
        WebsitePartnerLogo::create([
            'name'          => 'Linkedin',
        ]);
        WebsitePartnerLogo::create([
            'name'          => 'Youtube',
        ]);
        WebsitePartnerLogo::create([
            'name'          => 'OpenAI',
        ]);
        WebsitePartnerLogo::create([
            'name'          => 'Instagram',
        ]);
        WebsitePartnerLogo::create([
            'name'          => 'Meta',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('website_partner_logos');
    }
};
