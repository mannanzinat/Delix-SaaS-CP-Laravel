<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Client\AiWriterController;
use App\Http\Controllers\Client\BotReplyController;
use App\Http\Controllers\Client\ClientDashboardController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\ContactNoteController;
use App\Http\Controllers\Client\ContactsListController;
use App\Http\Controllers\Client\ContactTagController;
use App\Http\Controllers\Client\FlowBuilderController;
use App\Http\Controllers\Client\MessageController;
use App\Http\Controllers\Client\SegmentController;
use App\Http\Controllers\Client\SettingController;
use App\Http\Controllers\Client\SubscriptionController;
use App\Http\Controllers\Client\TeamController;
use App\Http\Controllers\Client\TelegramCampaignController;
use App\Http\Controllers\Client\TemplateController;
use App\Http\Controllers\Client\TicketController;
use App\Http\Controllers\Client\UserController;
use App\Http\Controllers\Client\WhatsappCampaignController;
use Illuminate\Support\Facades\Route;

Route::get('available-plans', [SubscriptionController::class, 'availablePlans'])->name('available.plans');
Route::get('pending-subscription', [SubscriptionController::class, 'pendingSubscription'])->name('pending.subscription');
Route::get('upgrade-plan/{id}', [SubscriptionController::class, 'upgradePlan'])->name('upgrade.plan');
Route::post('offline-claim', [SubscriptionController::class, 'offlineClaim'])->name('offline.claim');
Route::post('stripe-redirect', [SubscriptionController::class, 'stripeRedirect'])->name('stripe.redirect');
Route::get('stripe-success', [SubscriptionController::class, 'stripeSuccess'])->name('stripe.payment.success');
Route::post('paypal-redirect', [SubscriptionController::class, 'paypalRedirect'])->name('paypal.redirect');
Route::get('paypal-success', [SubscriptionController::class, 'paypalSuccess'])->name('paypal.payment.success');
Route::post('paddle-redirect', [SubscriptionController::class, 'paddleRedirect'])->name('paddle.redirect');
Route::get('paddle-success', [SubscriptionController::class, 'paddleSuccess'])->name('paddle.payment.success');
Route::get('back-to-admin', [AuthenticatedSessionController::class, 'back_to_admin'])->name('back.to.admin');
// Route::group(['prefix' => localeRoutePrefix().'/client', 'middleware' => 'subscriptionCheck'], function () {
Route::group(['prefix' => localeRoutePrefix().'/client'], function () {
    // susbcription
    Route::get('my-subscription', [SubscriptionController::class, 'mySubscription'])->name('my.subscription');

    Route::middleware(['authCheck', 'subscriptionCheck'])->group(function () {
        Route::get('dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
    });

    Route::get('whatsapp-settings', [SettingController::class, 'whatsAppSettings'])->name('whatsapp.settings');
    Route::get('telegram-settings', [SettingController::class, 'telegramSettings'])->name('telegram.settings');
    Route::get('general-settings', [SettingController::class, 'generalSettings'])->name('general.settings');
    Route::post('general-settings/{id}', [SettingController::class, 'updateGeneralSettings'])->name('general.settings.update');
    Route::post('whatsapp-settings/sync', [SettingController::class, 'whatsAppsync'])->name('whatsapp-settings.sync');

    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('telegram/settings', [SettingController::class, 'telegramUpdate'])->name('settings.telegram.update');
    Route::post('telegram/settings/remove-token/{id}', [SettingController::class, 'removeTelegramToken'])->name('settings.remove-token');
    Route::post('whatsApp/settings/remove-token/{id}', [SettingController::class, 'removeWhatsAppToken'])->name('whatsAppSettings.remove-token');
    Route::get('api', [SettingController::class, 'api'])->name('settings.api');
    Route::post('api', [SettingController::class, 'update_api'])->name('settings.api.update');
    //Chat
    Route::get('chat', [MessageController::class, 'index'])->name('chat.index');
    Route::get('profile', [ClientDashboardController::class, 'profile'])->name('profile.index');
    Route::patch('update', [ClientDashboardController::class, 'profileUpdate'])->name('profile.update');
    Route::get('password-change', [ClientDashboardController::class, 'passwordChange'])->name('profile.password-change');
    Route::post('password-update', [ClientDashboardController::class, 'passwordUpdate'])->name('profile.password-update');
    //billing
    Route::get('billing/details', [SettingController::class, 'billingDetails'])->name('billing.details');
    Route::post('billing/details/store/{id}', [SettingController::class, 'storeBillingDetails'])->name('billing.details.store');

    Route::middleware(['whatsapp.connected'])->group(function () {
        //templates route
        Route::get('telegram/templates', [TemplateController::class, 'getTelegramTemplate'])->name('telegram.templates.index');
        Route::get('templates', [TemplateController::class, 'index'])->name('templates.index');
        Route::get('template/load-templete', [TemplateController::class, 'loadTemplate'])->name('templates.load-templete');
        Route::get('template/create', [TemplateController::class, 'create'])->name('template.create');
        Route::post('template/store', [TemplateController::class, 'store'])->name('template.store');
        Route::get('template/get/{id}', [TemplateController::class, 'getTemplateByID'])->name('template.get');
        Route::get('template/sync/{id}', [TemplateController::class, 'syncTemplateByID'])->name('template.sync');
        Route::post('template/delete/{id}', [TemplateController::class, 'delete'])->name('template.delete');
        Route::get('template/edit/{id}', [TemplateController::class, 'edit'])->name('template.edit');
        Route::post('template/update/{id}', [TemplateController::class, 'update'])->name('template.update');

        //campaigns route
        Route::get('whatsapp/campaigns', [WhatsappCampaignController::class, 'index'])->name('whatsapp.campaigns.index');
        Route::get('whatsapp/campaign/create', [WhatsappCampaignController::class, 'create'])->name('whatsapp.campaign.create');
        Route::post('whatsapp/campaign/store', [WhatsappCampaignController::class, 'store'])->name('whatsapp.campaign.store');
        Route::post('whatsapp/contact-template/store', [WhatsappCampaignController::class, 'storeContactTemplate'])->name('whatsapp.contact.template.store');
        Route::get('whatsapp/overview', [WhatsappCampaignController::class, 'overview'])->name('whatsapp.overview');
        Route::get('whatsapp/campaigns/{id}/view', [WhatsappCampaignController::class, 'view'])->name('whatsapp.campaigns.view');
        Route::post('whatsapp/campaigns/status/update/{id}', [WhatsappCampaignController::class, 'statusUpdate'])->name('whatsapp.campaigns.status.update');
        Route::post('whatsapp/campaigns/filter', [WhatsappCampaignController::class, 'filterData'])->name('whatsapp.campaigns.filter');
        Route::post('whatsapp/campaign/resend', [WhatsappCampaignController::class, 'resend'])->name('whatsapp.campaign.resend');
        Route::get('send-template', [WhatsappCampaignController::class, 'sendTemplate'])->name('send.template');
        Route::get('whatsapp/campaign/count-contact', [WhatsappCampaignController::class, 'campaignCountContact'])->name('whatsapp.campaign.count-contact');


        
    });
    Route::middleware(['telegram.connected'])->group(function () {
        Route::get('telegram/campaigns', [TelegramCampaignController::class, 'index'])->name('telegram.campaigns.index');
        Route::get('telegram/campaign/create', [TelegramCampaignController::class, 'create'])->name('telegram.campaign.create');
        Route::post('telegram/campaign/store', [TelegramCampaignController::class, 'store'])->name('telegram.campaign.store');
        Route::get('telegram/campaigns/{id}/view', [TelegramCampaignController::class, 'view'])->name('telegram.campaigns.view');
        Route::post('telegram/campaigns/status/update/{id}', [TelegramCampaignController::class, 'statusUpdate'])->name('telegram.campaigns.status.update');
        Route::get('telegram/overview', [TelegramCampaignController::class, 'overview'])->name('telegram.overview');
        Route::get('telegram/groups', [TelegramCampaignController::class, 'groups'])->name('groups.index');
    });
    //segments route
    Route::get('segments', [SegmentController::class, 'index'])->name('segments.index');
    Route::post('segment/store', [SegmentController::class, 'store'])->name('segment.store');
    Route::get('segment/edit/{id}', [SegmentController::class, 'edit'])->name('segment.edit');
    Route::post('segment/update/{id}', [SegmentController::class, 'update'])->name('segment.update');
    Route::delete('segment/delete/{id}', [SegmentController::class, 'delete'])->name('segment.delete');
    Route::get('segments-list', [ContactController::class, 'segments'])->name('segment.list');

    //contacts route
    Route::get('telegram/subscribers/list', [ContactController::class, 'getTelegramContact'])->name('telegram.subscribers.index');
    Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('contact/create', [ContactController::class, 'create'])->name('contact.create');
    Route::post('contact/store', [ContactController::class, 'store'])->name('contact.store');
    Route::get('contact/edit/{id}', [ContactController::class, 'edit'])->name('contact.edit');
    Route::post('contact/update/{id}', [ContactController::class, 'update'])->name('contact.update');
    Route::delete('contact/delete/{id}', [ContactController::class, 'delete'])->name('contact.delete');

    Route::get('contact/view/{id}', [ContactController::class, 'view'])->name('contact.view');


    Route::post('/contact/blacklist', [ContactController::class, 'addBlacklist'])->name('contact.blacklist');
    Route::post('/remove-blacklist', [ContactController::class, 'removeBlacklist'])->name('remove.blacklist');
    Route::get('/contact/add-blacklist/{id}', [ContactController::class, 'block'])->name('contact.add_blacklist');
    Route::get('/contact/remove-blacklist/{id}', [ContactController::class, 'unblock'])->name('contact.remove_blacklist');

    Route::post('/add-list', [ContactController::class, 'addList'])->name('add.list');
    Route::post('/remove-list', [ContactController::class, 'removeList'])->name('remove.list');
    Route::post('/add-segment', [ContactController::class, 'addSegment'])->name('add.segment');
    Route::post('/remove-segment', [ContactController::class, 'removeSegment'])->name('remove.segment');
    Route::post('contact/parse-csv', [ContactController::class, 'parseCSV'])->name('contact.parse.csv');
    Route::post('contact/confirm-upload', [ContactController::class, 'confirmUpload'])->name('contact.confirm-upload');
    Route::get('contact/import', [ContactController::class, 'createImport'])->name('contact.import');

    //contacts list route
    Route::get('contacts-list', [ContactsListController::class, 'index'])->name('contacts_list.index');
    Route::post('contacts-store', [ContactsListController::class, 'store'])->name('contacts_list.store');
    Route::get('contacts-list/edit/{id}', [ContactsListController::class, 'edit'])->name('list.edit');
    Route::post('contact-list/update/{id}', [ContactsListController::class, 'update'])->name('list.update');
    Route::get('contacts-export', [ContactsListController::class, 'downloadSample'])->name('contacts.export');
    Route::post('contacts-Imports-store', [ContactsListController::class, 'importStore'])->name('store.Imports');
    Route::delete('contacts-list/delete/{id}', [ContactsListController::class, 'delete'])->name('list.delete');

    //tickets route
    Route::resource('tickets', TicketController::class)->except(['edit', 'destroy']);
    Route::get('ticket/update/{id}', [TicketController::class, 'update'])->name('ticket.update');
    Route::post('ticket-reply', [TicketController::class, 'reply'])->name('ticket.reply');
    Route::get('ticket-reply-edit/{id}', [TicketController::class, 'replyEdit'])->name('ticket.reply.edit');
    Route::post('ticket-reply-update/{id}', [TicketController::class, 'replyUpdate'])->name('ticket.reply.update');
    Route::delete('ticket-reply-delete/{id}', [TicketController::class, 'replyDelete'])->name('ticket.reply.delete');

    //Bot Reply
    Route::resource('bot-reply', BotReplyController::class);
    //team route
    Route::get('team-list', [TeamController::class, 'index'])->name('team.index');
    Route::get('team/create', [TeamController::class, 'create'])->name('team.create');
    Route::post('team/store', [TeamController::class, 'store'])->name('team.store');
    Route::get('team/edit/{id}', [TeamController::class, 'edit'])->name('team.edit');
    Route::put('team/update/{id}', [TeamController::class, 'update'])->name('team.update');

    //AI writer
    Route::get('ai-writer', [AiWriterController::class, 'index'])->name('ai.writer');
    Route::post('ai-writer', [AiWriterController::class, 'saveAiSetting'])->name('ai.writer');
    Route::get('ai-writer-setting', [SettingController::class, 'aiWriterSetting'])->name('ai_writer.setting');
    Route::post('generated-ai-content', [AiWriterController::class, 'generateContent'])->name('ai.content');

    //user route
    Route::get('users/verified/{verify}', [UserController::class, 'instructorVerified'])->name('users.verified');
    Route::get('users/ban/{id}', [UserController::class, 'instructorBan'])->name('users.ban');
    Route::post('user-status', [UserController::class, 'statusChange'])->name('users.status');
    Route::delete('users/delete/{id}', [UserController::class, 'instructorDelete'])->name('users.delete');

    Route::post('onesignal-subscription', [UserController::class, 'oneSignalSubscription'])->name('onesignal');
    Route::get('onesignal-notification', [UserController::class, 'oneSignalNotification'])->name('onesignal.notification');
    Route::delete('stop-recurring/{id}', [SubscriptionController::class, 'stopRecurring'])->name('stop.recurring');
    Route::delete('enable-recurring/{id}', [SubscriptionController::class, 'enableRecurring'])->name('enable.recurring');
    Route::delete('cancel-subscription/{id}', [SubscriptionController::class, 'cancelSubscription'])->name('cancel.subscription');

});

Route::group(['prefix' => 'client', 'middleware' => 'subscriptionCheck'], function () {
    Route::get('contacts-by-client', [ContactController::class, 'contactByClient'])->name('contacts.by.client');
    Route::get('staffs-by-client', [MessageController::class, 'staffsByClient'])->name('staffs-by-client');
    Route::get('chat-rooms', [MessageController::class, 'chatRooms'])->name('chat.rooms');
    Route::get('message/{chat_room_id}', [MessageController::class, 'chatroomMessages'])->name('chatroom.messages');
    Route::post('send-message', [MessageController::class, 'sendMessage'])->name('message.sent');
    Route::get('canned-responses', [BotReplyController::class, 'cannedResponses'])->name('canned.responses');
    Route::post('assign-staff', [MessageController::class, 'assignStaff'])->name('assign.staff');
    Route::get('contacts-details/{id}', [MessageController::class, 'contactDetails'])->name('contacts.details');
    Route::resource('notes', ContactNoteController::class)->only(['store', 'destroy']);
    Route::get('tags', [ContactTagController::class, 'index'])->name('tags');
    Route::post('tags', [ContactTagController::class, 'store'])->name('tags.store');
    Route::post('tags/change-status', [ContactTagController::class, 'changeStatus'])->name('tags.change.status');
    Route::post('tags/change-status', [ContactTagController::class, 'changeStatus'])->name('tags.change.status');
    Route::get('shared-files/{id}', [MessageController::class, 'sharedFiles'])->name('shared.files');
    Route::delete('delete-file/{id}', [MessageController::class, 'deleteFile'])->name('delete.file');
    Route::get('whatsapp-templates', [TemplateController::class, 'whatsappTemplates'])->name('whatsapp.templates');
    Route::get('send-template', [WhatsappCampaignController::class, 'sendTemplate'])->name('send.template');
    Route::resource('flow-builders', FlowBuilderController::class)->except(['update']);
    Route::post('flow-builders/{id}', [FlowBuilderController::class,'update'])->name('flow-builders.update');
    Route::post('flow-builders/{id}', [FlowBuilderController::class,'update'])->name('flow-builders.update');

    Route::post('upload-files', [FlowBuilderController::class, 'uploadFile'])->name('upload.file');




    
});

Route::get('chat-refresh', function () {
    \Illuminate\Support\Facades\Artisan::call('chat:refresh');
    return 'success';
})->name('chat.refresh');
