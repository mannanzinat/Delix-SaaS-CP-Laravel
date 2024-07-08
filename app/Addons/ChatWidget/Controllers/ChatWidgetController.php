<?php

namespace App\Addons\ChatWidget\Controllers;

use App\Models\Timezone;
use App\Models\ChatWidget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ChatWidgetContact;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Addons\ChatWidget\Datatable\ChatWidgetDataTable;
use App\Addons\ChatWidget\Repository\ChatWidgetRepository;

class ChatWidgetController extends Controller
{
    protected $repo;
    protected $widget_default_settings;
    public function __construct(ChatWidgetRepository $repo)
    {
        $this->repo = $repo;
        if (!addon_is_activated('chat_widget')) {
            abort(403, 'Unauthorized action.');
        }
        $this->widget_default_settings = config('static_array.widget_default_settings');

    }

    public function index(ChatWidgetDataTable $dataTable)
    {
        return $dataTable->render('addon:ChatWidget::index');
    }

    public function create()
    {
        return view('addon:ChatWidget::create');
    }

    public function store(Request $request)
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }
        $request->validate([
            'name' => 'required',
        ]);

        return $this->repo->store($request);
    }

    public function edit($id)
    {
        try {
            $data = [
                'row' => $this->repo->find($id),
            ];

            return view('addon:ChatWidget::edit', $data);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            
            return back();
        }
    }

    public function getEmbadCode($id)
    {
        return  $this->repo->getEmbadCode($id);
    }


    public function view($id)
    {
        try {
            $data = [
                'row' => $this->repo->find($id),
                'time_zones' => Timezone::all(),

            ];

            return view('addon:ChatWidget::view', $data);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back();
        }
    }


    public function update(Request $request, $id)
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];

            return response()->json($data);
        }
        $request->validate([
            'name' => 'required',
            'available_days' => 'required',
        ]);

        return  $this->repo->update($request, $id);
    }


    public function updateContactSortOrder(Request $request)
    {
        return  $this->repo->updateContactSortOrder($request);
    }

    public function destroy($id)
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];
            return response()->json($data);
        }
        return  $this->repo->destroy($id);
    }

    public function reset($id)
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];
            return response()->json($data);
        }
        return  $this->repo->reset($id);
    }

    public function updateButton(Request $request, $id)
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];
            return response()->json($data);
        }
        $request->validate([
            'enable_box' => 'required',
            'box_position' => 'required',
            'rounded_border' => 'required',
            'button_text' => 'required|string',
            'phone' => $request->enable_box == 0 ? 'required|string' : '',
        ]);

        return  $this->repo->updateButton($request, $id);
    }

    public function updateBox(Request $request, $id)
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];
            return response()->json($data);
        }
        $request->validate([
            'auto_open'         => 'required',
            'header_title'      => 'required|string',
            'header_subtitle'   => 'nullable|string',
        ]);
        return  $this->repo->updateBox($request, $id);
    }
    public function updateSettings(Request $request, $id)
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];
            return response()->json($data);
        }
        $request->validate([
            'background_color'  => 'required|string',
            'header_background_color' => 'required|string',
            'text_color'        => 'required|string',
            'label_color'       => 'required|string',
            'name_color'        => 'required|string',
            'font_size'         => 'required|string',
            'icon_size'         => 'required|string',
            'availability_color' => 'required|string',
        ]);
        return  $this->repo->updateSettings($request, $id);
    }


    public function contactStore(Request $request)
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];
            return response()->json($data);
        }
        $request->validate([
            'name' => 'required|string',
            'widget_id' => 'required|string',
            'phone' => 'required|string',
            'label' => 'required|string',
            'welcome_message' => 'required|string',
            'available_from' => 'required|string',
            'available_to' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        return $this->repo->contactStore($request);
    }




    public function contactUpdate(Request $request, $id)
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];
            return response()->json($data);
        }

        $request->validate([
            'name' => 'required|string',
            'widget_id' => 'required|string',
            'phone' => 'required|string',
            'label' => 'required|string',
            'welcome_message' => 'required|string',
            'available_from' => 'required|string',
            'available_to' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);
        return  $this->repo->contactUpdate($request, $id);
    }


    public function contactDestroy($id)
    {
        if (isDemoMode()) {
            $data = [
                'status'  => 'danger',
                'message' => __('this_function_is_disabled_in_demo_server'),
                'title'   => 'error',
            ];
            return response()->json($data);
        }
        return $this->repo->contactDestroy($id);
    }

    public function statusUpdate($id)
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];
            return response()->json($data);
        }
        return $this->repo->statusUpdate($id);
    }

    public function contactStatusUpdate($id)
    {
        if (isDemoMode()) {
            $data = [
                'status' => 'danger',
                'error'  => __('this_function_is_disabled_in_demo_server'),
                'title'  => 'error',
            ];
            return response()->json($data);
        }
        return $this->repo->contactStatusUpdate($id);
    }

    public function contactEdit($id)
    {
        return $this->repo->contactEdit($id);
    }

    public function getQRImage($id)
    {
        $contact = ChatWidgetContact::where('unique_id',$id)->first();
        if (empty($contact)) {
            abort(404);
        }
    
        $contactName = $contact->name ?? '';
        $contactPhone = $contact->phone ?? '';
        $contactLabel = $contact->label ?? '';
        $initialMessage = $contact->welcome_message;
    
        $isMobile = stripos($_SERVER['HTTP_USER_AGENT'], 'android') !== false || 
                    stripos($_SERVER['HTTP_USER_AGENT'], 'iphone') !== false || 
                    stripos($_SERVER['HTTP_USER_AGENT'], 'ipad') !== false;
    
        $value = $isMobile 
            ? 'https://api.whatsapp.com/send?phone=' . $contactPhone . '&text=' . urlencode($initialMessage)
            : 'https://web.whatsapp.com/send?phone=' . $contactPhone . '&text=' . urlencode($initialMessage);
    
        $qrName = $contactLabel . '_' . $contactName . '_qr_';
        $baseName = Str::slug($qrName);
        $fileName = $baseName . uniqid() . ".png";
        $path = public_path('client/qr-code/');
    
        // Ensure the directory exists
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
        
        $filePath = $path . $fileName;
        QrCode::format('png')
        ->size(800)
        ->margin(5) // Set the margin to 20 pixels
        ->color(74, 74, 74, 80)
        ->generate($value, $filePath);
        DB::table('chat_widget_contacts')->where('unique_id', $id)->increment('total_qr_download', 1);
        return Response::download($filePath);
    }

    public function determineAvailabilityStatus($widget, $contact)
    {
        $timezone = $widget->timezone ? $widget->timezone : ($widget->client->timezone ?? config('app.timezone'));
        // Get the widget's timezone
        $widgetTimezone = new \DateTimeZone($timezone);
        // Get the current time in the widget's timezone
        $currentDateTime = new \DateTime('now', $widgetTimezone);
        $currentDay = strtolower($currentDateTime->format('l'));
        $currentTime = $currentDateTime->format('H:i:s');

        // Get the available days and times from the widget and contact
        $availableDays = $widget->available_days;
        $availableFrom = $contact->available_from;
        $availableTo = $contact->available_to;

        // Determine the availability status
        if (in_array($currentDay, $availableDays)) {
            if ($currentTime >= $availableFrom && $currentTime <= $availableTo) {
                return 'available';
            }
        }
        
        return 'away';
    }

    public function generateWhatsAppLink($contactPhone, $initialMessage)
    {
        $android = stripos($_SERVER['HTTP_USER_AGENT'], 'android');
        $iphone = stripos($_SERVER['HTTP_USER_AGENT'], 'iphone');
        $ipad = stripos($_SERVER['HTTP_USER_AGENT'], 'ipad');
        $isMobile = $android !== false || $ipad !== false || $iphone !== false;
        if ($isMobile) {
            return "https://api.whatsapp.com/send?phone=$contactPhone&text=" . urlencode($initialMessage);
        } else {
            return "https://web.whatsapp.com/send?phone=$contactPhone&text=" . urlencode($initialMessage);
        }
    }

    public function generateScript(Request $request)
    { 
        $defaultSettings = $this->widget_default_settings;
        $default_value = null;
        $boxPosition = $defaultSettings['box_position'];
        $animationType = $defaultSettings['animation'];
        $auto_open = $defaultSettings['auto_open'];
        $font_size = $defaultSettings['font_size'];
        $font_family = $defaultSettings['font_family'];
        $text_color = $defaultSettings['text_color'];
        $enable_box = $defaultSettings['enable_box'];
        $auto_open_delay = $defaultSettings['auto_open_delay'];
        $android = stripos($_SERVER['HTTP_USER_AGENT'], 'android');
        $iphone = stripos($_SERVER['HTTP_USER_AGENT'], 'iphone');
        $ipad = stripos($_SERVER['HTTP_USER_AGENT'], 'ipad');
        $id = $request->id;
        $widget =  ChatWidget::with(['contacts'])->active()->where('unique_id', $id)->first();
        if ($widget) {
            $widget->increment('total_hit');
        }
        $baseURL = static_asset('css/chat-widget.css');
        if ($widget) {
            $boxPosition    = $widget->box_position;
            $labelColor     = $widget->label_color;
            $nameColor      = $widget->name_color;
            $headerTitle    = $widget->header_title ?? '';
            $headerSubtitle = $widget->header_subtitle ?? '';
            $buttonText     = $widget->button_text;
            $header_bg      = $widget->header_background_color;
            $background_color = $widget->background_color;
            $rounded_border = $widget->rounded_border == 1 ? 'rounded-border' : 'no-rounded-border';
            $auto_open      = $widget->auto_open;
            $font_size      = $widget->font_size;
            $text_color     = $widget->text_color;
            $icon_size     = $widget->icon_size ?? 16;
            $font_family     = $widget->font_family;
            $icon_font_size     = $widget->icon_font_size;
            $phone          = $widget->phone;
            $default_welcome_message = $widget->welcome_message;
            $auto_open_delay      = $widget->auto_open_delay;
            $layout         = $widget->layout;
            $enable_box     = $widget->enable_box;
            $devices = $widget->devices;
            $animationType = $widget->animation;
            $displayClass = null;
            $displayClass = 'sb-hide';
            if ($android !== false || $ipad !== false || $iphone !== false) {
                $default_value = "https://api.whatsapp.com/send?phone=" . $phone . "&text=" . urlencode($default_welcome_message);
            } else {
                $default_value = "https://web.whatsapp.com/send?phone=" . $phone . "&text=" . urlencode($default_welcome_message);
            }

            $htmlContent = <<<HTML
                <div id='sb-chat-widget' class='sb-chat-widget $rounded_border sb-hide' style="background:$background_color !important;">
                    <div class="sb-widget-container">
                        <div class="widget-box">
                            <div class='widget-header' style="background-color:$header_bg">
                                <div class='head-home'>
                                    <p class="text-white"><span class="whatsapp-name">$headerTitle</span><br><small>$headerSubtitle</small></p>
                                </div>
                            </div>
                            <div class="widget-body">
                HTML;
                foreach ($widget->contacts as $contact) {
                    $contactName = $contact->name ?? '';
                    $contactPhone = $contact->phone ?? '';
                    $contactLabel = $contact->label ?? '';
                    $avatar = getFileLink('80x80', $contact->images) ?? '';
                    $initialMessage = $contact->welcome_message;
                    $value = $this->generateWhatsAppLink($contactPhone, $initialMessage);
                    $availabilityStatus = $this->determineAvailabilityStatus($widget, $contact);
                    $htmlContent .= <<<HTML
                                        <a href="$value" class="widget-account" data-action="open" data-phone="$contactPhone" role="button" tabindex="0" target="_blank">
                                            <div class="widget-avatar">
                                                <div class="widget-avatar-container">
                                                    <img alt="$contactName" src="$avatar" class="sp-img-fluid">
                                                </div>
                                                <div class="sb-status $availabilityStatus"></div>
                                            </div>
                                            <div class="widget-info">
                                                <span class="widget-label" style="color:$labelColor">$contactLabel</span>
                                                <span class="widget-name" style="color:$nameColor">$contactName</span>
                                            </div>
                                        </a>
                                    HTML;
                }
            $htmlContent .= <<<HTML
                            </div>
                            <div id='close-widget'></div><a class='close-chat' href='javascript:void(0)'>×</a>
                        </div>
                    </div>
                </div>
            HTML;
            if ($layout == 'button') {
                if ($enable_box == 1) {
                    $buttonHTML = <<<HTML
                    <a id="salebot-chat-btn" class='salebot-chat $rounded_border ' href='javascript:void(0)' title='Show Chat'>
                        <svg width="$icon_size" viewBox="0 0 24 24"><defs/><path fill="#eceff1" d="M20.5 3.4A12.1 12.1 0 0012 0 12 12 0 001.7 17.8L0 24l6.3-1.7c2.8 1.5 5 1.4 5.8 1.5a12 12 0 008.4-20.3z"/><path fill="#4caf50" d="M12 21.8c-3.1 0-5.2-1.6-5.4-1.6l-3.7 1 1-3.7-.3-.4A9.9 9.9 0 012.1 12a10 10 0 0117-7 9.9 9.9 0 01-7 16.9z"/><path fill="#fafafa" d="M17.5 14.3c-.3 0-1.8-.8-2-.9-.7-.2-.5 0-1.7 1.3-.1.2-.3.2-.6.1s-1.3-.5-2.4-1.5a9 9 0 01-1.7-2c-.3-.6.4-.6 1-1.7l-.1-.5-1-2.2c-.2-.6-.4-.5-.6-.5-.6 0-1 0-1.4.3-1.6 1.8-1.2 3.6.2 5.6 2.7 3.5 4.2 4.2 6.8 5 .7.3 1.4.3 1.9.2.6 0 1.7-.7 2-1.4.3-.7.3-1.3.2-1.4-.1-.2-.3-.3-.6-.4z"/></svg>
                        $buttonText
                    </a>
                HTML;
                } else {
                    $buttonHTML = <<<HTML
                    <a class='salebot-chat $rounded_border $layout' target="_blank" href='$default_value' title='Show Chat'>
                        <svg width="$icon_size" viewBox="0 0 24 24"><defs/><path fill="#eceff1" d="M20.5 3.4A12.1 12.1 0 0012 0 12 12 0 001.7 17.8L0 24l6.3-1.7c2.8 1.5 5 1.4 5.8 1.5a12 12 0 008.4-20.3z"/><path fill="#4caf50" d="M12 21.8c-3.1 0-5.2-1.6-5.4-1.6l-3.7 1 1-3.7-.3-.4A9.9 9.9 0 012.1 12a10 10 0 0117-7 9.9 9.9 0 01-7 16.9z"/><path fill="#fafafa" d="M17.5 14.3c-.3 0-1.8-.8-2-.9-.7-.2-.5 0-1.7 1.3-.1.2-.3.2-.6.1s-1.3-.5-2.4-1.5a9 9 0 01-1.7-2c-.3-.6.4-.6 1-1.7l-.1-.5-1-2.2c-.2-.6-.4-.5-.6-.5-.6 0-1 0-1.4.3-1.6 1.8-1.2 3.6.2 5.6 2.7 3.5 4.2 4.2 6.8 5 .7.3 1.4.3 1.9.2.6 0 1.7-.7 2-1.4.3-.7.3-1.3.2-1.4-.1-.2-.3-.3-.6-.4z"/></svg>
                        $buttonText
                    </a>
                HTML;
                }
            } elseif ($layout == 'bubble') {
                if (!empty($phone) && $enable_box == 0) {
                    $buttonHTML = <<<HTML
                        <a class='salebot-chat $rounded_border $layout layout-bubble' target="_blank" href='$default_value' title='Show Chat'>
                            <svg width="$icon_size" viewBox="0 0 24 24"><defs/><path fill="#eceff1" d="M20.5 3.4A12.1 12.1 0 0012 0 12 12 0 001.7 17.8L0 24l6.3-1.7c2.8 1.5 5 1.4 5.8 1.5a12 12 0 008.4-20.3z"/><path fill="#4caf50" d="M12 21.8c-3.1 0-5.2-1.6-5.4-1.6l-3.7 1 1-3.7-.3-.4A9.9 9.9 0 012.1 12a10 10 0 0117-7 9.9 9.9 0 01-7 16.9z"/><path fill="#fafafa" d="M17.5 14.3c-.3 0-1.8-.8-2-.9-.7-.2-.5 0-1.7 1.3-.1.2-.3.2-.6.1s-1.3-.5-2.4-1.5a9 9 0 01-1.7-2c-.3-.6.4-.6 1-1.7l-.1-.5-1-2.2c-.2-.6-.4-.5-.6-.5-.6 0-1 0-1.4.3-1.6 1.8-1.2 3.6.2 5.6 2.7 3.5 4.2 4.2 6.8 5 .7.3 1.4.3 1.9.2.6 0 1.7-.7 2-1.4.3-.7.3-1.3.2-1.4-.1-.2-.3-.3-.6-.4z"/></svg>
                        </a>
                    HTML;
                } else {
                    $buttonHTML = <<<HTML
                        <a  id="salebot-chat-btn" class='salebot-chat $boxPosition $rounded_border $layout layout-bubble' href='javascript:void(0)' title='Show Chat'>
                            <svg width="$icon_size" viewBox="0 0 24 24"><defs/><path fill="#eceff1" d="M20.5 3.4A12.1 12.1 0 0012 0 12 12 0 001.7 17.8L0 24l6.3-1.7c2.8 1.5 5 1.4 5.8 1.5a12 12 0 008.4-20.3z"/><path fill="#4caf50" d="M12 21.8c-3.1 0-5.2-1.6-5.4-1.6l-3.7 1 1-3.7-.3-.4A9.9 9.9 0 012.1 12a10 10 0 0117-7 9.9 9.9 0 01-7 16.9z"/><path fill="#fafafa" d="M17.5 14.3c-.3 0-1.8-.8-2-.9-.7-.2-.5 0-1.7 1.3-.1.2-.3.2-.6.1s-1.3-.5-2.4-1.5a9 9 0 01-1.7-2c-.3-.6.4-.6 1-1.7l-.1-.5-1-2.2c-.2-.6-.4-.5-.6-.5-.6 0-1 0-1.4.3-1.6 1.8-1.2 3.6.2 5.6 2.7 3.5 4.2 4.2 6.8 5 .7.3 1.4.3 1.9.2.6 0 1.7-.7 2-1.4.3-.7.3-1.3.2-1.4-.1-.2-.3-.3-.6-.4z"/></svg>
                        </a>
                    HTML;
                }
            }
            $htmlContent .= $buttonHTML;
        } else {
            $htmlContent = '';
        }
        $widgetConfig = [
            'poweredByText' => '&copy;Powered by <a target="_BLANK" href="">Salebot</a>',
        ];
        $js = <<<JS
        /*
        Project: SaleBot WhatsApp Chat Widget
        Author: Spagreen Creative team
        Website: https://spagreen.net/
        All rights reserved by Spagreen Creative.
        */
        "use strict";
        const animationType = '{$animationType}';
        const chatWidgetBody =`$htmlContent`;;
        const widgetConfig = {
            poweredByText: '{$widgetConfig['poweredByText']}',
            auto_open: {$auto_open},
            font_size: '{$font_size}',
            font_family: '{$font_family}',
            text_color: '{$text_color}',
            enable_box: '{$enable_box}'
        };
        function initializeWidget() {
            addStyles();
            setTimeout(() => {
                createWidget();
                const chatWidget = document.getElementById("sb-chat-widget");
                if (chatWidget) {
                if (widgetConfig.auto_open === 1 && widgetConfig.enable_box == 1) {
                    console.log(1);
                    const delay = {$auto_open_delay} || 300; 
                    setTimeout(() => {
                        chatWidget.classList.add("sb-show");
                        chatWidget.classList.add(animationType);
                        chatWidget.classList.remove("sb-hide");
                    }, delay);
                }
                else if(widgetConfig.enable_box==0){
                    chatWidget.classList.remove("sb-show");
                    chatWidget.classList.add("sb-hide");
                }
                else{
                    console.log(widgetConfig.auto_open);
                    console.log(widgetConfig.enable_box);
                    chatWidget.classList.remove("sb-show");
                    chatWidget.classList.add("sb-hide");
                }
            } else {
            }

            }, 1000);
        }
        function addStyles() {
            const cssURL = "{$baseURL}";
            addStyleLink(cssURL);
            let additionalStyles = '';
            if (widgetConfig.font_family) {
                additionalStyles += '#sb-chat-widget { font-family: ' + widgetConfig.font_family + '}';
                additionalStyles += '.salebot-chat { font-family: ' + widgetConfig.font_family + '}';
            }
            if (widgetConfig.font_size) {
                additionalStyles += '#sb-chat-widget .widget-body { font-size: ' + widgetConfig.font_size + 'px!important; }';
            }
            if (widgetConfig.text_color) {
                additionalStyles += '#sb-chat-widget .widget-body { color: ' + widgetConfig.text_color + '!important; }';
            }
            if (additionalStyles) {
                addStyle(additionalStyles);
            }
        }
        function addStyleLink(url) {
            const link = document.createElement("link");
            link.type = "text/css";
            link.rel = "stylesheet";
            link.href = url;
            document.head.appendChild(link);
        }
        function addStyle(cssContent) {
            const styleTag = document.createElement("style");
            styleTag.textContent = cssContent;
            document.head.appendChild(styleTag);
        }
        function createWidget() {
            const wrapper = document.createElement('div');
            wrapper.innerHTML = chatWidgetBody;
            wrapper.classList.add('sb-chat-wrapper');
            wrapper.classList.add('$boxPosition');
            document.body.appendChild(wrapper);
        }
        document.addEventListener("click", function(event) {
          if (event.target && event.target.classList.contains("sb-inform")) {
            document.querySelector(".home-chat, .head-home").classList.add("sb-hide");
            document.querySelector(".home-chat, .head-home").classList.remove("sb-show");
          }
        });
        document.addEventListener("click", function(event) {
        const chatWidget = document.getElementById("sb-chat-widget");
        if (!chatWidget) return;
            const enableBox = {$enable_box};
            if (enableBox === 0) {
                chatWidget.classList.add("sb-hide");
                return;
            }
            if (event.target && event.target.classList.contains("salebot-chat")) {
                if (chatWidget.classList.contains("sb-hide")) {
                    chatWidget.classList.remove("sb-hide");
                    chatWidget.classList.add("sb-show");
                } else {
                    chatWidget.classList.remove("sb-show");
                    chatWidget.classList.add("sb-hide");
                }
            }
            if (event.target && event.target.classList.contains("close-chat")) {
                chatWidget.classList.add("sb-hide");
                chatWidget.classList.remove("sb-show");
            }
        }); 
        document.addEventListener('DOMContentLoaded', initializeWidget);
        JS;
        return response($js)->header('Content-Type', 'text/javascript');
    }
}
