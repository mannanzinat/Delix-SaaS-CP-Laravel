<?php

use App\Models\User;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('last_name', 50)->nullable();
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('phone_country_id')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('password');
            $table->json('permissions')->nullable();
            $table->enum('user_type', ['admin', 'stuff', 'client-staff']);
            $table->string('firebase_auth_id')->nullable()->comment('this is for mobile app.');
            $table->bigInteger('language_id')->unsigned()->nullable();
            $table->bigInteger('currency_id')->unsigned()->nullable();
            $table->tinyInteger('status')->default(1)->comment('0 inactive, 1 active');
            $table->text('images')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamp('last_password_change')->nullable();
            $table->tinyInteger('is_user_banned')->default(0)->comment('0 not banned, 1 banned');
            $table->tinyInteger('is_deleted')->default(0)->comment('0 not delete, 1 deleted');
            $table->bigInteger('role_id')->unsigned()->nullable();
            $table->string('address')->nullable();
            $table->bigInteger('country_id')->unsigned()->nullable();
            $table->text('about')->nullable();
            $table->tinyInteger('is_newsletter_enabled')->default(0)->comment('1=newsletter enable, 0= not newsletter enable');
            $table->tinyInteger('is_notification_enabled')->default(0)->comment('1=Notification enable, 0= not Notification enable');
            $table->string('onesignal_player_id')->nullable();
            $table->boolean('is_onesignal_subscribed')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        $now  = now();
        $data = [
            [
                'first_name'        => 'Super',
                'last_name'         => 'Admin',
                'email'             => 'admin@spagreen.net',
                'password'          => bcrypt('123456'),
                'phone'             => '017111131111',
                'address'           => 'Dhaka, Bangladesh',
                'user_type'         => 'admin',
                'role_id'           => 1,
                'email_verified_at' => $now,
                'created_at'        => $now,
                'updated_at'        => $now,
                'about'             => '',
            ],
        ];

        User::insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
