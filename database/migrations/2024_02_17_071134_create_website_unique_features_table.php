<?php

use App\Models\WebsiteUniqueFeature;
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
        Schema::create('website_unique_features', function (Blueprint $table) {
            $table->id();
            $table->text('icon');
            $table->text('image');
            $table->string('title');
            $table->longText('description');
            $table->enum('status', [0, 1,])->default(1);
            $table->timestamps();
        });

        WebsiteUniqueFeature::create([
            'title'                         => 'User-Friendly Interface to Easy Use',
            'description'                   => 'Experience seamless navigation with our robust UI/UX design, made for effortless user interaction.en',
        ]);

        WebsiteUniqueFeature::create([
            'title'                         => 'User-Friendly Interface to Easy Use',
            'description'                   => 'Experience seamless navigation with our robust UI/UX design, made for effortless user interaction.en',
        ]);

        WebsiteUniqueFeature::create([
            'title'                         => 'User-Friendly Interface to Easy Use',
            'description'                   => 'Experience seamless navigation with our robust UI/UX design, made for effortless user interaction.en',
        ]);
        WebsiteUniqueFeature::create([
            'title'                         => 'User-Friendly Interface to Easy Use',
            'description'                   => 'Experience seamless navigation with our robust UI/UX design, made for effortless user interaction.en',
        ]);
        WebsiteUniqueFeature::create([
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
        Schema::dropIfExists('website_unique_features');
    }
};
