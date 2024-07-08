<?php

use App\Models\EmailTemplate;
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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->string('identifier');
            $table->string('title')->nullable();
            $table->longText('body')->nullable();
            $table->string('short_codes')->nullable();
            $table->string('email_type');
            $table->tinyInteger('status')->default(1)->comment('0 inactive, 1 active');
            $table->timestamps();
        });

        $now  = now();
        $data = [
            'subject'     => 'SMTP Configuration Test',
            'identifier'  => 'test_email',
            'title'       => 'SMTP Test Mail',
            'short_codes' => '{name},{email},{site_name},{login_link}',
            'body'        => 'Great News!!Email is working Perfectly.',
            'email_type'  => 'system',
            'created_at'  => $now,
            'updated_at'  => $now,
        ];
        EmailTemplate::insert($data);
        $data = [
            'Subject'     => 'Confirm your email',
            'identifier'  => 'confirmation_email',
            'title'       => 'Email Confirmation',
            'short_codes' => '{name},{email},{site_name},{confirmation_link}',
            'body'        => '<p>Hi {name},</p><p>Please confirm your email by clicking the link below:</p><p>{confirmation_link}</p><p><br></p><p>Thanks</p><p>{site_name}</p>',
            'email_type'  => 'user',
            'created_at'  => $now,
            'updated_at'  => $now,
        ];
        EmailTemplate::insert($data);
        $data = [
            'subject'     => 'Welcome to',
            'identifier'  => 'welcome_email',
            'title'       => 'Welcome Email',
            'short_codes' => '{name},{email},{site_name},{login_link}',
            'body'        => 'Welcome to {site_name}',
            'email_type'  => 'user',
            'created_at'  => $now,
            'updated_at'  => $now,
        ];
        EmailTemplate::insert($data);
        $data = [
            'Subject'     => 'Reset your password',
            'identifier'  => 'password_reset_email',
            'title'       => 'password_reset',
            'short_codes' => '{name},{email},{site_name},{reset_link}',
            'body'        => 'Hi {name},<br>Reset your password using below link:',
            'email_type'  => 'user',
            'created_at'  => $now,
            'updated_at'  => $now,
        ];
        EmailTemplate::insert($data);
        $data = [
            'subject'     => 'Your password has been changed',
            'identifier'  => 'recovery_email',
            'title'       => 'recovery_mail',
            'short_codes' => '{name},{email},{site_name},{login_link}',
            'body'        => 'Email temple is working Perfectly!! This is Recovery Successful email template from',
            'email_type'  => 'user',
            'created_at'  => $now,
            'updated_at'  => $now,
        ];
        EmailTemplate::insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_templates');
    }
};
