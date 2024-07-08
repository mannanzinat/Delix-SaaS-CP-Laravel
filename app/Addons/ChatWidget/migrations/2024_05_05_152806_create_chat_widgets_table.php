<?php

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
        if (!Schema::hasTable('chat_widgets')) {
            Schema::create('chat_widgets', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('unique_id')->unique();
                $table->boolean('enable_box')->default(0);
                $table->enum('box_position', [
                    'middle-left',
                    'middle-right',
                    'bottom-left',
                    'bottom-right',
                ])->default('bottom-right');
                $table->enum('layout', [
                    'button',
                    'bubble',
                ])->default('bubble');
                $table->time('schedule_from')->nullable();
                $table->time('schedule_to')->nullable();
                $table->string('timezone', 100)->nullable();
                $table->string('available_days')->nullable();
                $table->enum('visibility', [
                    'readonly',
                    'hidden',
                ])->default('hidden');
                $table->enum('type', [
                    'phone',
                    'group',
                ])->default('phone');
                $table->enum('devices', [
                    'all',
                    'mobile',
                    'desktop',
                    'hide',
                ])->default('all');
                $table->string('phone',20)->nullable();
                $table->string('welcome_message')->nullable();
                $table->string('offline_message')->nullable();
                $table->string('header_title')->nullable();
                $table->string('header_subtitle')->nullable();
                $table->string('header_media')->nullable();
                $table->string('footer_text')->nullable();
                $table->enum('font_family', [
                    'inherit',
                    'Arial',
                    'Verdana',
                    'Helvetica',
                    'Tahoma',
                    'Trebuchet MS',
                    'Times New Roman',
                    'Georgia',
                    'Garamond',
                    'Courier New',
                    'Brush Script MT',
                    'Calibri',
                ])->default('inherit');
                $table->enum('animation', [
                    'none',
                    'bounce',
                    'flash',
                    'pulse',
                    'shakeY',
                    'shakeX',
                ])->default('none');
                $table->boolean('auto_open')->default(true);
                $table->integer('auto_open_delay')->default(0);
                $table->integer('animation_delay')->nullable();
                $table->string('font_size')->nullable();
                $table->boolean('rounded_border')->default(0);
                $table->string('background_color', 20)->nullable();
                $table->string('header_background_color', 20)->nullable();
                $table->string('background_image', 20)->nullable();
                $table->string('text_color', 20)->nullable();
                $table->string('icon_size', 20)->nullable();
                $table->string('icon_font_size', 20)->nullable();
                $table->string('label_color', 20)->nullable();
                $table->string('name_color', 20)->nullable();
                $table->string('button_text', 150)->nullable();
                $table->string('availability_color', 20)->nullable();
                $table->boolean('store_chat_history')->default(false);
                $table->bigInteger('total_hit')->default(0);
                $table->string('custom_style')->nullable();
                $table->json('analytics_settings')->nullable();
                $table->json('buttons')->nullable();
                $table->boolean('status')->default(true);
                $table->unsignedBigInteger('client_id')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         // Drop trigger
        DB::statement('DROP TRIGGER IF EXISTS generate_unique_id_after_insert');
        Schema::dropIfExists('chat_widgets');
    }
};
