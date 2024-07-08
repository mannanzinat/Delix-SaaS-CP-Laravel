<?php
use Illuminate\Support\Facades\Route;
use App\Addons\ChatWidget\Controllers\ChatWidgetController;
Route::get('/chat-widget.jsx', 'ChatWidgetController@generateScript')->name('chat-widget-script');
//Route::get('/chat-widget.{extension}', 'ChatWidgetController@generateScript')
    //->where('extension', 'js|jsx')
    //->name('chat-widget-script');
 
Route::group(['prefix' => localeRoutePrefix().'/client', 'middleware' => ['web', 'auth', 'verified']], function () {
    Route::get('chat-widget/list', [ChatWidgetController::class, 'index'])->name('client.chatwidget.index');
    Route::get('chat-widget/create', [ChatWidgetController::class, 'create'])->name('client.chatwidget.create');
    Route::post('chat-widget/destroy/{id}', [ChatWidgetController::class, 'destroy'])->name('client.chatwidget.destroy');
    Route::post('chat-widget/store', [ChatWidgetController::class, 'store'])->name('client.chatwidget.store');
    Route::post('chat-widget/update/{id}', [ChatWidgetController::class, 'update'])->name('client.chatwidget.update');
    Route::get('chat-widget/view/{id}', [ChatWidgetController::class, 'view'])->name('client.chatwidget.view');
    Route::post('chat-widget/status/update/{id}', [ChatWidgetController::class, 'statusUpdate'])->name('client.chatwidget.status.update');
    Route::post('chatwidget/update-button/{id}', [ChatWidgetController::class, 'updateButton'])->name('client.chatwidget.update-button');
    Route::post('chatwidget/update-box/{id}', [ChatWidgetController::class, 'updateBox'])->name('client.chatwidget.update-box');
    Route::post('chatwidget/update-settings/{id}', [ChatWidgetController::class, 'updateSettings'])->name('client.chatwidget.update-settings');
    Route::get('chat-widget/embad-code/{id}', [ChatWidgetController::class, 'getEmbadCode'])->name('client.chatwidget.embad-code');
    Route::post('chat-widget/reset-setting/{id}', [ChatWidgetController::class, 'reset'])->name('client.chatwidget.reset-setting');
    Route::get('chat-widget/contact/edit/{id}', [ChatWidgetController::class, 'contactEdit'])->name('client.chatwidget.contact.edit');
    Route::post('chat-widget/contact/destroy/{id}', [ChatWidgetController::class, 'contactDestroy'])->name('client.chatwidget.contact.destroy');
    Route::post('chat-widget/contact/store', [ChatWidgetController::class, 'contactStore'])->name('client.chatwidget.contact.store');
    Route::post('chat-widget/contact/update/{id}', [ChatWidgetController::class, 'contactUpdate'])->name('client.chatwidget.contact.update');
    Route::post('chat-widget/contact/status/update/{id}', [ChatWidgetController::class, 'contactStatusUpdate'])->name('client.chatwidget.contact.status-update');
    Route::post('chat-widget/contact/sort', [ChatWidgetController::class, 'updateContactSortOrder'])->name('client.chatwidget.contact.sort');
    Route::get('contact/qr-download/{id}', [ChatWidgetController::class, 'getQRImage'])->name('client.chatwidget.contact.qr-download');
});
