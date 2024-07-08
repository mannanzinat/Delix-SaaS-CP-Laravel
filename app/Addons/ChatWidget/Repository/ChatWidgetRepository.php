<?php

namespace App\Addons\ChatWidget\Repository;

use App\Models\ChatWidget;
use App\Traits\ImageTrait;
use Illuminate\Support\Str;
use App\Traits\RepoResponse;
use App\Models\ChatWidgetContact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ChatWidgetRepository
{
    use ImageTrait, RepoResponse;

    private $model;
    private $contact;
    private $widget_default_settings;
    public function __construct(
        ChatWidget $model,
        ChatWidgetContact $contact
    ) {
        $this->model = $model;
        $this->contact = $contact;
        $this->widget_default_settings = config('static_array.widget_default_settings');
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $widget = new $this->model;
            $defaultSettings = $this->widget_default_settings;
            $widget->fill([
                'name'              => $request->name,
                'welcome_message'   => $request->welcome_message ?? $defaultSettings['welcome_message'],
                'offline_message'   => $request->offline_message ?? $defaultSettings['offline_message'],
                'unique_id'         => $this->make_unique_id(),
                'box_position'      => $request->box_position ?? $defaultSettings['box_position'],
                'layout'            => $request->layout ?? $defaultSettings['layout'],
                'schedule_from'     => $request->schedule_from ?? $defaultSettings['schedule_from'],
                'schedule_to'       => $request->schedule_to ?? $defaultSettings['schedule_to'], 
                'timezone'          => $request->timezone ? $request->timezone : (Auth::user()->client->timezone ?? config('app.timezone')),
                'available_days'    => $request->available_days ??array_values(config('static_array.available_days')),
                'visibility'        => $request->visibility ?? $defaultSettings['visibility'],
                'type'              => $request->type ?? $defaultSettings['type'],
                'devices'           => $request->devices ?? $defaultSettings['devices'],
                'header_media'      => $request->header_media ?? $defaultSettings['header_media'],
                'header_background_color'=> $request->header_background_color ?? $defaultSettings['header_background_color'],
                'text_color'        => $request->text_color ?? $defaultSettings['text_color'],
                'icon_font_size'    => $request->icon_size ?? $defaultSettings['icon_size'],
                'button_text'       => $request->button_text ?? $defaultSettings['button_text'],
                'enable_box'        => $request->enable_box ?? $defaultSettings['enable_box'],
                'header_title'      => $request->header_title ?? $defaultSettings['header_title'],
                'header_subtitle'   => $request->header_subtitle ?? $defaultSettings['header_subtitle'],
                'footer_text'       => $request->footer_text ?? $defaultSettings['footer_text'],
                'font_family'       => $request->font_family ?? $defaultSettings['font_family'],
                'animation'         => $request->animation ?? $defaultSettings['animation'],
                'auto_open'         => $request->auto_open ?? $defaultSettings['auto_open'],
                'auto_open_delay'   => $request->auto_open_delay ?? $defaultSettings['auto_open_delay'],
                'animation_delay'   => $request->animation_delay ?? $defaultSettings['animation_delay'],
                'font_size'         => $request->font_size ?? $defaultSettings['font_size'],
                'rounded_border'    => $request->rounded_border ?? $defaultSettings['rounded_border'],
                'background_color'  => $request->background_color ?? $defaultSettings['background_color'],
                'background_image'  => $request->background_image ?? $defaultSettings['background_image'],
                'text_color'        => $request->text_color ?? $defaultSettings['text_color'],
                'icon_size'         => $request->icon_size ?? $defaultSettings['icon_size'],
                'label_color'       => $request->label_color ?? $defaultSettings['label_color'],
                'name_color'        => $request->name_color ?? $defaultSettings['name_color'],
                'availability_color' => $request->availability_color ?? $defaultSettings['availability_color'],
                'client_id'         => Auth::user()->client->id,
                'status'            => $request->status ?? 1,
            ]);
            $widget->save();
            DB::commit();
            return $this->formatResponse(true, __('created_successfully'), route('client.chatwidget.view', $widget->id), []);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), route('client.chatwidget.view', $widget->id), []);
        }
    }

    public function find($id)
    {
        return $this->model->withPermission()->find($id);
    }

    public function update($request, $id)
    {
        $defaultSettings = $this->widget_default_settings;
        DB::beginTransaction();
        try {
            $widget                     = $this->model->withPermission()->findOrfail($id);
            $widget->name               = $request->name;
            $widget->timezone           = $request->timezone ? $request->timezone : (Auth::user()->client->timezone ?? config('app.timezone'));
            $widget->devices            = $request->devices ?? $defaultSettings['devices'];
            $widget->schedule_from      = $request->schedule_from ?? $defaultSettings['schedule_from'];
            $widget->schedule_to        = $request->schedule_to ?? $defaultSettings['schedule_to'];
            $widget->available_days     = $request->available_days ??  array_values(config('static_array.available_days'));
            $widget->welcome_message    = $request->welcome_message ?? $defaultSettings['welcome_message'];
            $widget->offline_message    = $request->offline_message ?? $defaultSettings['offline_message'];
            $widget->save();
            DB::commit();

            return $this->formatResponse(true, __('updated_successfully'), route('client.chatwidget.view', $widget->id), []);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), route('client.chatwidget.view', $widget->id), []);
        }
    }

    public function updateButton($request, $id)
    {
        $defaultSettings = $this->widget_default_settings;
        DB::beginTransaction();
        try {
            $widget                     = $this->model->withPermission()->findOrfail($id);
            $widget->phone              = $request->phone;
            $widget->enable_box         = $request->enable_box ?? $defaultSettings['enable_box'];
            $widget->box_position       = $request->box_position ?? $defaultSettings['box_position'];
            $widget->layout             = $request->layout ?? $defaultSettings['layout'];
            $widget->rounded_border     = $request->rounded_border ?? $defaultSettings['rounded_border'];
            $widget->button_text        = $request->button_text ?? $defaultSettings['button_text'];
            $widget->save();
            DB::commit();
            return $this->formatResponse(true, __('updated_successfully'), route('client.chatwidget.view', $widget->id), []);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), route('client.chatwidget.view', $widget->id), []);
        }
    }
    public function updateBox($request, $id)
    {
        DB::beginTransaction();
        try {
            $defaultSettings           = $this->widget_default_settings;
            $widget                    = $this->model->withPermission()->findOrfail($id);
            $widget->auto_open         = $request->auto_open ?? $defaultSettings['auto_open'];
            $widget->auto_open_delay   = $request->auto_open_delay ?? $defaultSettings['auto_open_delay'];
            $widget->header_title      = $request->header_title ?? $defaultSettings['header_title'];
            $widget->header_subtitle   = $request->header_subtitle ?? $defaultSettings['header_subtitle'];
            $widget->footer_text       = $request->footer_text ?? $defaultSettings['footer_text'];
            $widget->save();
            DB::commit();
            return $this->formatResponse(true, __('updated_successfully'), route('client.chatwidget.view', $widget->id), []);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), route('client.chatwidget.view', $widget->id), []);
        }
    }

    public function updateSettings($request, $id)
    {
        $defaultSettings           = $this->widget_default_settings;
        DB::beginTransaction();
        try {
            $widget                     = $this->model->withPermission()->findOrfail($id);
            $widget->font_size          = $request->font_size ?? $defaultSettings['font_size'];
            $widget->background_color   = $request->background_color ?? $defaultSettings['background_color'];
            $widget->text_color         = $request->text_color ?? $defaultSettings['text_color'];
            $widget->icon_size          = $request->icon_size ?? $defaultSettings['icon_size'];
            $widget->icon_font_size     = $request->icon_font_size ?? $defaultSettings['icon_font_size'];
            $widget->label_color        = $request->label_color ?? $defaultSettings['label_color'];
            $widget->name_color         = $request->name_color ?? $defaultSettings['name_color'];
            $widget->availability_color = $request->availability_color ?? $defaultSettings['availability_color'];
            $widget->header_background_color = $request->header_background_color ?? $defaultSettings['header_background_color'];
            $widget->save();
            DB::commit();
            return $this->formatResponse(true, __('updated_successfully'), route('client.chatwidget.view', $widget->id), []);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), route('client.chatwidget.view', $widget->id), []);
        }
    }


    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->contact->where('widget_id', $id)->delete();
            $widget = $this->model->withPermission()->findOrFail($id);
            $widget->delete();
            DB::commit();
            return $this->formatResponse(true, __('deleted_successfully'), '', []);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), '', []);
        }
    }
    
    public function reset($id)
    {
        DB::beginTransaction();
        try {
            $widget =  $this->model->withPermission()->find($id);
            $defaultSettings = $this->widget_default_settings;
            $widget->fill([
                'welcome_message'   => $defaultSettings['welcome_message'],
                'offline_message'   => $defaultSettings['offline_message'],
                'button_text'       => $defaultSettings['button_text'],
                'enable_box'        => $defaultSettings['enable_box'],
                'header_title'      => $defaultSettings['header_title'],
                'header_subtitle'   => $defaultSettings['header_subtitle'],
                'footer_text'       => $defaultSettings['footer_text'],
                'font_family'       => $defaultSettings['font_family'],
                'animation'         => $defaultSettings['animation'],
                'auto_open'         => $defaultSettings['auto_open'],
                'auto_open_delay'   => $defaultSettings['auto_open_delay'],
                'animation_delay'   => $defaultSettings['animation_delay'],
                'font_size'         => $defaultSettings['font_size'],
                'rounded_border'    => $defaultSettings['rounded_border'],
                'background_color'  => $defaultSettings['background_color'],
                'background_image'  => $defaultSettings['background_image'],
                'text_color'        => $defaultSettings['text_color'],
                'icon_size'         => $defaultSettings['icon_size'],
                'icon_font_size'    => $defaultSettings['icon_font_size'],
                'label_color'       => $defaultSettings['label_color'],
                'name_color'        => $defaultSettings['name_color'],
                'availability_color' => $defaultSettings['availability_color'],
                'header_background_color' => $defaultSettings['header_background_color'],
                'box_position'          => $defaultSettings['box_position'],
            ]);
            $widget->save();

            DB::commit();
            return $this->formatResponse(true, __('updated_successfully'), '', []);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), '', []);
        }
    }

    public function getEmbadCode($id)
    {
        try {
            $row = $this->model->withPermission()->find($id);
            $data = [
                'row' => $row,
            ];
            $results = view('addon:ChatWidget::partials.__embade_code_modal_body', $data)->render();

            return $this->formatResponse(true, __('data_found'), '', $results);
        } catch (\Throwable $e) {
                return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), '', []);
        }
    }

    public function contactStore($request)
    {
        DB::beginTransaction();
        try {
            $conatct                    = new $this->contact;
            $conatct->unique_id         = $this->contact_unique_id();
            $conatct->name              = $request->name;
            $conatct->username          = Str::slug($request->name);
            $conatct->label             = $request->label;
            $conatct->welcome_message   = $request->welcome_message;
            $conatct->available_from    = $request->available_from;
            $conatct->available_to      = $request->available_to;
            $conatct->timezone          = $request->timezone ? $request->timezone : (Auth::user()->client->timezone ?? config('app.timezone'));
            $conatct->widget_id         = $request->widget_id;
            $conatct->priority          = $this->contact->where('widget_id',$request->widget_id)->max('priority') + 1;
            $conatct->status            = $request->status ?? 1;
            $conatct->phone             = str_replace(' ', '', $request->phone);
            if ($request->hasFile('image')) {
                $requestImage           = $request['image'];
                $response               = $this->saveImage($requestImage, '_staff_');
                $conatct->images        = $response['images'];
            }
            $conatct->save();
            DB::commit();
            $data = [
                'row' => $conatct->chatwidget,
            ];
            $results = view('addon:ChatWidget::partials.__contact_list', $data)->render();

            return $this->formatResponse(true, __('created_successfully'), route('client.chatwidget.view', $conatct->widget_id), $results);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), route('client.chatwidget.view', $conatct->widget_id), []);
        }
    }

    public function contactUpdate($request, $id)
    {
        DB::beginTransaction();
        try {
            $conatct                    = $this->contact->findOrFail($id);
            $conatct->name              = $request->name;
            $conatct->username          = Str::slug($request->name);
            $conatct->label             = $request->label;
            $conatct->welcome_message   = $request->welcome_message;
            $conatct->available_from    = $request->available_from;
            $conatct->available_to      = $request->available_to;
            $conatct->timezone          = $request->timezone ? $request->timezone : (Auth::user()->client->timezone ?? config('app.timezone'));
            $conatct->phone             = str_replace(' ', '', $request->phone);
            $conatct->priority          = $request->priority;
            if ($request->hasFile('image')) {
                $requestImage           = $request['image'];
                $response               = $this->saveImage($requestImage, '_staff_');
                $conatct->images        = $response['images'];
            }
            $conatct->save();
            DB::commit();
            $data = [
                'row' => $conatct->chatwidget,
            ];
            $results = view('addon:ChatWidget::partials.__contact_list', $data)->render();
            return $this->formatResponse(true, __('updated_successfully'), route('client.chatwidget.view', $conatct->widget_id), $results);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), route('client.chatwidget.view', $conatct->widget_id), []);
        }
    }

    public function contactDestroy($id)
    {
        DB::beginTransaction();
        try {
            $contact = $this->contact->findOrFail($id);
            $contact->delete();
            $chatwidget = $contact->chatwidget;
            DB::commit();
            $data = [
                'row' => $chatwidget,
            ];
            $results = view('addon:ChatWidget::partials.__contact_list', $data)->render();
            return $this->formatResponse(true, __('deleted_successfully'), '', $results);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return $this->formatResponse(false, __('an_unexpected_error_occurred_please_try_again_later.'), '', []);
        }
    }

    public function make_unique_id()
    {
        return 'CW' . rand(10000000000, 99999999999);
    }

    public function contact_unique_id()
    {
        return Str::upper(uniqid());
    }

    public function statusUpdate($id)
    {
        try {
            $chatwidget         = $this->model->find($id);
            $chatwidget->status = $chatwidget->status == 1 ? 0 : 1;
            $chatwidget->save();
            return $this->formatResponse(true, __('updated_successfully'), 'client.chatwidget.index', $chatwidget);
        } catch (\Throwable $th) {
            return $this->formatResponse(false, $th->getMessage(), 'client.chatwidget.view', []);
        }
    }

    public function contactStatusUpdate($id)
    {
        try {
            $chatwidget         = $this->contact->find($id);
            $chatwidget->status = $chatwidget->status == 1 ? 0 : 1;
            $chatwidget->save();
            return $this->formatResponse(true, __('updated_successfully'), 'client.chatwidget.index', $chatwidget);
        } catch (\Throwable $th) {
            return $this->formatResponse(false, $th->getMessage(), 'client.chatwidget.view', []);
        }
    }

    public function contactEdit($id)
    {
        try {
            $contact = $this->contact->findOrFail($id);
            $data = [
                'row' => $contact,
            ];
            $results = view('addon:ChatWidget::partials.__edit_contact_body', $data)->render();
            return $this->formatResponse(true, '', '', $results);
        } catch (\Throwable $e) {
            return $this->formatResponse(false, __('data_not_found'), '', []);
        }
    }

    public function updateContactSortOrder($request)
    {
        DB::beginTransaction();
        try {
            $ids = $request->input('order'); // Assuming you're sending the IDs of the items in the desired order
            $order = 1;
            foreach ($ids as $id) {
                $model = ChatWidgetContact::find($id);
                $model->priority = $order;
                $model->save();
                $order++;
            }
            DB::commit();
            return $this->formatResponse(true, __('updated_successfully'), 'client.chatwidget.index', []);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (config('app.debug')) {
                dd($e->getMessage());            
            }
            return $this->formatResponse(false, __('data_not_found'), '', []);
        }
    }
}
