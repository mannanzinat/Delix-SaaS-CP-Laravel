<?php

use App\Models\Permission;
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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 151);
            $table->string('attribute')->nullable();
            $table->mediumtext('keywords')->nullable();
            $table->timestamps();
        });

        $attributes       = [
            'dashboard'       => [
                'dashboard'            => 'admin.dashboard',
                'dashboard statistics' => 'dashboard_statistic',
            ],
            'manage_client'   => [
                'view'          => 'client.index',
                'create'        => 'client.create',
                'edit'          => 'client.edit',
                'delete'        => 'client.destroy',
                'client_log_in' => 'client.log_in',
            ],
            'price_plans'     => [
                'view'   => 'price_plans.index',
                'create' => 'price_plans.create',
                'edit'   => 'price_plans.edit',
                'delete' => 'price_plans.destroy',
            ],
            'Staff'           => [
                'create'       => 'staffs.create',
                'view'         => 'staffs.index',
                'edit'         => 'staffs.edit',
                'roles'        => 'roles.index',
                'staff_delete' => 'staffs.delete',
                'change_role'  => 'staffs.change-role',
            ],
            'roles'           => [
                'view'   => 'roles.index',
                'create' => 'roles.create',
                'edit'   => 'roles.edit',
                'delete' => 'roles.destroy',
            ],
            'ai_assistant'    => [
                'ai_writer'  => 'ai.writer',
                'ai_setting' => 'ai.setting',
            ],
            'payment_methods' => [
                'view' => 'payment_methods.index',
                'edit' => 'payment_methods.edit',
            ],
            'subscription'    => [
                'view'   => 'subscription.index',
                'edit'   => 'subscription.edit',
                'delete' => 'subscription.destroy',
            ],
            'Notification'    => [
                'view'   => 'custom-notification.index',
                'create' => 'custom-notification.create',
                'edit'   => 'custom-notification.edit',
                'delete' => 'custom-notification.destroy',
            ],
            'Support System'  => [
                'view'                => 'tickets.index',
                'create'              => 'tickets.create',
                'edit'                => 'tickets.edit',
                'ticket_reply'        => 'ticket.reply',
                'ticket_reply_edit'   => 'ticket.reply.edit',
                'ticket_reply_delete' => 'ticket.reply.delete',
            ],
            'departments'     => [
                'view'   => 'departments.index',
                'create' => 'departments.create',
                'edit'   => 'departments.edit',
                'delete' => 'departments.destroy',
            ],
            'website setting' => [
                'website_themes' => 'website.themes',
                'section_title'  => 'section.title',
                'hero_section'   => 'hero.section',
                'ai_chat'        => 'ai.chat',
                'call_to_action' => 'website.cta',
                'footer_content' => 'footer.content',
                'website_seo'    => 'website_setting.seo',
                'custom_js'      => 'website_setting.custom_js',
                'custom_css'     => 'website_setting.custom_css',
                'google_setup'   => 'website_setting.google_setup',
                'facebook_pixel' => 'website_setting.fb_pixel',
            ],
            'partner_logo'    => [
                'view'   => 'partner_logo.index',
                'create' => 'partner_logo.create',
                'edit'   => 'partner_logo.edit',
                'delete' => 'partner_logo.destroy',
            ],
            'story_section'   => [
                'view'   => 'story_section.index',
                'create' => 'story_section.create',
                'edit'   => 'story_section.edit',
                'delete' => 'story_section.destroy',
            ],
            'unique_feature'  => [
                'view'   => 'unique_feature.index',
                'create' => 'unique_feature.create',
                'edit'   => 'unique_feature.edit',
                'delete' => 'unique_feature.destroy',
            ],
            'feature'         => [
                'view'   => 'feature.index',
                'create' => 'feature.create',
                'edit'   => 'feature.edit',
                'delete' => 'feature.destroy',
            ],
            'testimonials'    => [
                'view'   => 'testimonials.index',
                'create' => 'testimonials.create',
                'edit'   => 'testimonials.edit',
                'delete' => 'testimonials.destroy',
            ],
            'advantage'       => [
                'view'   => 'advantage.index',
                'create' => 'advantage.create',
                'edit'   => 'advantage.edit',
                'delete' => 'advantage.destroy',
            ],
            'faqs'            => [
                'view'   => 'faqs.index',
                'create' => 'faqs.create',
                'edit'   => 'faqs.edit',
                'delete' => 'faqs.destroy',
            ],
            'email_setting'   => [
                'server_configuration'      => 'email.server_configuration',
                'edit_server_configuration' => 'email.server_configuration.edit',
                'email_template'            => 'email.template',
                'edit_template'             => 'email_template.edit',
            ],
            'users'           => [
                'users_verified' => 'user.verified',
                'users_ban'      => 'user.ban',
                'user_status'    => 'status',
                'users_delete'   => 'delete',
            ],
            'system_setting'  => [
                'general_setting'         => 'general.setting',
                'preference'              => 'preference',
                'cache'                   => 'admin.cache',
                'admin_panel_setting'     => 'admin.panel-setting',
                'firebase'                => 'admin.firebase',
                'storage_setting'         => 'storage.setting',
                'chat_messenger'          => 'chat.messenger',
                'miscellaneous_setting'   => 'miscellaneous.setting',
                'cron_setting'            => 'cron.setting',
                'pusher_notification'     => 'pusher.notification',
                'one_signal_notification' => 'onesignal.notification',

            ],
            'currencies'      => [
                'view'                => 'currencies.index',
                'create'              => 'currencies.create',
                'edit'                => 'currencies.edit',
                'delete'              => 'currencies.destroy',
                'default currency'    => 'currencies.default-currency',
                'set currency format' => 'set.currency.format',
            ],
            'languages'       => [
                'view'                  => 'languages.index',
                'create'                => 'languages.create',
                'edit'                  => 'languages.edit',
                'delete'                => 'languages.destroy',
                'language_translations' => 'language.translations.page',
                'update_translations'   => 'admin.language.key.update',
            ],
            'countries'       => [
                'view'   => 'countries.index',
                'create' => 'countries.create',
                'edit'   => 'countries.edit',
                'delete' => 'countries.destroy',
            ],
            'addons'          => [
                'view'   => 'addon.index',
                'create' => 'addon.create',
                'edit'   => 'addon.edit',
                'delete' => 'addon.destroy',
            ],
            'utility'         => [
                'system_update'          => 'system.update',
                'server_information'     => 'server.info',
                'extension_library'      => 'extension.library',
                'file_system_permission' => 'file.system.permission',
            ],

        ];

        $admin_permission = [];

        Permission::whereNotNull('id')->delete();

        foreach ($attributes as $key => $attribute) {
            $permission            = new Permission;
            $permission->name      = str_replace('_', ' ', $key);
            $permission->attribute = $key;
            $permission->keywords  = $attribute;
            $permission->save();
            foreach ($attribute as $index => $permit) {
                $admin_permission[] = trim($permit);
            }
            $user                  = User::first();
            $user->permissions     = $admin_permission;
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
};
