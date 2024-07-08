<?php

use App\Models\WebsiteUniqueFeatureLanguage;
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
        Schema::create('website_unique_feature_languages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_unique_feature_id');
            $table->string('lang');
            $table->string('title');
            $table->longText('description');
            $table->timestamps();
        });

        WebsiteUniqueFeatureLanguage::create([
            'website_unique_feature_id'          => '1',
            'lang'                      => 'en',
            'title'                         => 'User-Friendly Interface to Easy Use',
            'description'                   => 'Experience seamless navigation with our robust UI/UX design, made for effortless user interaction.en',
        ]);

        WebsiteUniqueFeatureLanguage::create([
            'website_unique_feature_id'          => '2',
            'lang'                      => 'en',
            'title'                         => 'User-Friendly Interface to Easy Use',
            'description'                   => 'Experience seamless navigation with our robust UI/UX design, made for effortless user interaction.en',
        ]);

        WebsiteUniqueFeatureLanguage::create([
            'website_unique_feature_id'          => '3',
            'lang'                      => 'en',
            'title'                         => 'User-Friendly Interface to Easy Use',
            'description'                   => 'Experience seamless navigation with our robust UI/UX design, made for effortless user interaction.en',
        ]);
        WebsiteUniqueFeatureLanguage::create([
            'website_unique_feature_id'          => '4',
            'lang'                      => 'en',
            'title'                         => 'User-Friendly Interface to Easy Use',
            'description'                   => 'Experience seamless navigation with our robust UI/UX design, made for effortless user interaction.en',
        ]);
        WebsiteUniqueFeatureLanguage::create([
            'website_unique_feature_id'          => '5',
            'lang'                      => 'en',
            'title'                         => 'User-Friendly Interface to Easy Use',
            'description'                   => 'Experience seamless navigation with our robust UI/UX design, made for effortless user interaction.en',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('website_unique_feature_languages');
    }
};
