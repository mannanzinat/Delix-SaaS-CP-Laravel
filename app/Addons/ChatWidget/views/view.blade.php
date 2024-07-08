@extends('backend.layouts.master')
@section('title', __('chatwidget'))
@push('css')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="section-title">{{ __('chatwidget') }}</h3>
                <div class="default-tab-list default-tab-list-v2  bg-white redious-border p-20 p-sm-30">
                    <ul class="nav pb-12 mb-20" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active ps-0" id="basicTab" data-bs-toggle="pill" data-bs-target="#basicInfo"
                                role="tab" aria-controls="basicInfo"
                                aria-selected="true">{{ __('basic_information') }}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="contactTab" data-bs-toggle="pill" data-bs-target="#contacts"
                                role="tab" aria-controls="contacts" aria-selected="false">{{ __('contacts') }}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="buttonsTab" data-bs-toggle="pill" data-bs-target="#buttons"
                                role="tab" aria-controls="buttons" aria-selected="false">{{ __('button') }}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="boxTab" data-bs-toggle="pill" data-bs-target="#box" role="tab"
                                aria-controls="box" aria-selected="false">{{ __('box') }}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="settingsTab" data-bs-toggle="pill" data-bs-target="#settings"
                                role="tab" aria-controls="settings" aria-selected="false">{{ __('settings') }}</a>
                        </li>
                    </ul>
                    @csrf
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="basicInfo" role="tabpanel" aria-labelledby="basicTab"
                            tabindex="0">
                            <form method="POST" action="{{ route('client.chatwidget.update', $row->id) }}"
                                enctype="multipart/form-data" id="update-chatwidget">
                                @csrf

                                <div class="row gx-20">
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="name" class="form-label">{{ __('name') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control rounded-2" id="name" name="name"
                                            placeholder="{{ __('name') }}" value="{{ old('name', $row->name) }}">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="available_days" class="form-label">
                                            {{ __('available_days') }}
                                        </label>
                                        <select id="available_days" name="available_days[]"
                                            class="multiple-select-1 js-example-basic-multiple form-select-lg rounded-0 mb-3"
                                            aria-label=".form-select-lg example" multiple>
                                            @foreach (config('static_array.available_days') as $key => $day)
                                                <option value="{{ $key }}"
                                                    @if (is_array($row->available_days)) {{ in_array($key, $row->available_days) ? 'selected' : '' }} @endif>
                                                    {{ ucfirst($day) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-4">
                                            <label for="timezone" class="form-label">{{ __('timezone') }}</label>
                                            <select class="form-select form-select-lg mb-3 with_search" name="timezone"
                                                id="timezone">
                                                @foreach ($time_zones as $time_zone)
                                                    <option 
                                                    value="{{ $time_zone->timezone}}"
                                                        {{ $time_zone->timezone == old('timezone',$row->timezone) ? 'selected' : '' }}>
                                                        {{ $time_zone->gmt_offset > 0 ? "(UTC +$time_zone->gmt_offset)" . ' ' . $time_zone->timezone : "(UTC $time_zone->gmt_offset)" .' '. $time_zone->timezone }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="nk-block-des text-danger">
                                                <p class="timezone error">{{ $errors->first('timezone') }}</p>
                                            </div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="welcome_message" class="form-label">{{ __('message') }}
                                        </label>
                                        <textarea class="form-control rounded-2" name="welcome_message" id="welcome_message" cols="30" rows="10"
                                            placeholder="{{ __('enter_welcome_message') }}">{{ $row->welcome_message }}</textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                 </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end align-items-center mt-30">
                                            <button id="" class="btn btn-primary d-none preloader"
                                                type="button" disabled>
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Loading...
                                            </button>
                                            <button type="submit"
                                                class="btn btn-primary save">{{ __('submit') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="contacts" role="tabpanel" aria-labelledby="contactTab"
                            tabindex="0">
                            <div class="oftions-content-right mb-12">
                                <a href="javascript:void(0)" class="d-flex align-items-center btn sg-btn-primary gap-2"
                                    data-bs-toggle="modal" data-bs-target="#addChatWidgetContact"><i
                                        class="las la-plus"></i>{{ __('add_new') }}</a>
                            </div>
                            <div class="row gx-20">
                                <div class="col-lg-12">
                                    <div class="staff-role-heigh simplebar">
                                        <div class="default-list-table table-responsive" id="append_contact">
                                            @include('addon:ChatWidget::partials.__contact_list', $row)
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="buttons" role="tabpanel" aria-labelledby="buttonsTab"
                            tabindex="0">
                            <form method="POST" action="{{ route('client.chatwidget.update-button', $row->id) }}"
                                enctype="multipart/form-data" class="" id="update-button">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="enable_box" class="form-label">
                                            {{ __('enable_box') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select id="enable_box" name="enable_box" class="form-select" aria-label=".form-select-lg example" required>
                                            <option value="1" {{ old('enable_box', $row->enable_box) == '1' ? 'selected' : '' }}>
                                                {{ __('enable') }}
                                            </option>
                                            <option value="0" {{ old('enable_box', $row->enable_box) == '0' ? 'selected' : '' }}>
                                                {{ __('disable') }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    
                                    <div class="col-md-4 col-12 mb-4" id="phoneInputWrapper" style="{{ $row->enable_box==1 ? 'display: none':'display: block' }};">
                                        <label for="phone" class="form-label">{{ __('phone') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control rounded-2" id="phone" name="phone" placeholder="{{ __('phone') }}" value="{{ old('phone', $row->phone) }}">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="box_position" class="form-label">{{ __('box_position') }}</label>
                                        <select id="box_position" name="box_position" class="form-select"
                                            aria-label=".form-select-lg example" required>
                                            <option value="middle-left"
                                                {{ old('box_position', $row->box_position) == 'none' ? 'selected' : '' }}>
                                                {{ __('middle_left') }}
                                            </option>
                                            <option value="middle-right"
                                                {{ old('box_position', $row->box_position) == 'middle-right' ? 'selected' : '' }}>
                                                {{ __('middle_right') }}
                                            </option>
                                            <option value="bottom-left"
                                                {{ old('box_position', $row->box_position) == 'bottom-left' ? 'selected' : '' }}>
                                                {{ __('bottom_left') }}
                                            </option>
                                            <option value="bottom-right"
                                                {{ old('box_position', $row->box_position) == 'bottom-right' ? 'selected' : '' }}>
                                                {{ __('bottom_right') }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="layout" class="form-label">{{ __('layout') }}</label>
                                        <select id="layout" name="layout" class="form-select"
                                            aria-label=".form-select-lg example" required>
                                            <option value="button"
                                                {{ old('layout', $row->layout) == 'button' ? 'selected' : '' }}>
                                                {{ __('button') }}
                                            </option>
                                            <option value="bubble"
                                                {{ old('layout', $row->layout) == 'bubble' ? 'selected' : '' }}>
                                                {{ __('bubble') }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="rounded_border" class="form-label">{{ __('rounded_border') }}</label>
                                        <select id="rounded_border" name="rounded_border" class="form-select"
                                            aria-label=".form-select-lg example" required>
                                            <option value="1"
                                                {{ old('rounded_border', $row->rounded_border) == '1' ? 'selected' : '' }}>
                                                {{ __('yes') }}
                                            </option>
                                            <option value="0"
                                                {{ old('rounded_border', $row->rounded_border) == '0' ? 'selected' : '' }}>
                                                {{ __('no') }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="button_text" class="form-label">{{ __('button_text') }}</label>
                                        <input type="text" class="form-control rounded-2" id="button_text"
                                            name="button_text" placeholder="{{ __('button_text') }}"
                                            value="{{ old('button_text', $row->button_text) }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end align-items-center mt-30">
                                            <button id="" class="btn btn-primary d-none preloader"
                                                type="button" disabled>
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Loading...
                                            </button>
                                            <button type="submit"
                                                class="btn btn-primary save">{{ __('submit') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="box" role="tabpanel" aria-labelledby="box" tabindex="0">
                            <form method="POST" action="{{ route('client.chatwidget.update-box', $row->id) }}"
                                enctype="multipart/form-data" id="update-box">
                                @csrf

                                <div class="row">
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="auto_open" class="form-label">{{ __('auto_open') }}</label>
                                        <select id="auto_open" name="auto_open" class="form-select"
                                            aria-label=".form-select-lg example" required>
                                            <option value="1"
                                                {{ old('auto_open', $row->auto_open) == '1' ? 'selected' : '' }}>
                                                {{ __('enable') }}
                                            </option>
                                            <option value="0"
                                                {{ old('auto_open', $row->auto_open) == '0' ? 'selected' : '' }}>
                                                {{ __('disable') }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="auto_open_delay"
                                            class="form-label">{{ __('auto_open_delay') }}</label>
                                        <input type="number" class="form-control rounded-2" id="auto_open_delay"
                                            name="auto_open_delay" placeholder="{{ __('auto_open_delay') }}"
                                            value="{{ old('auto_open_delay', $row->auto_open_delay) }}" value="1000" step="any">
                                            <span>{{ __('in_milliseconds') }}</span>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="header_title" class="form-label">{{ __('header_title') }}</label>
                                        <input type="text" class="form-control rounded-2" id="header_title"
                                            name="header_title" placeholder="{{ __('header_title') }}"
                                            value="{{ old('header_title', $row->header_title) }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="header_subtitle"
                                            class="form-label">{{ __('header_subtitle') }}</label>
                                        <input type="text" class="form-control rounded-2" id="header_subtitle"
                                            name="header_subtitle" placeholder="{{ __('header_subtitle') }}"
                                            value="{{ old('header_subtitle', $row->header_subtitle) }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end align-items-center mt-30">
                                            <button id="" class="btn btn-primary d-none preloader"
                                                type="button" disabled>
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Loading...
                                            </button>
                                            <button type="submit"
                                                class="btn btn-primary save">{{ __('submit') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="box" tabindex="0">
                            <form method="POST" action="{{ route('client.chatwidget.update-settings', $row->id) }}"
                                enctype="multipart/form-data" id="update-settings">
                                @csrf
                                <div class="row">
                                    
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="background_color"
                                            class="form-label">{{ __('background_color') }}</label>
                                        <input type="color" class="form-control rounded-2 color-picker"
                                            id="background_color" name="background_color"
                                            placeholder="{{ __('enter_background_color') }}"
                                            value="{{ old('background_color', $row->background_color) }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="header_background_color"
                                            class="form-label">{{ __('header_background_color') }}</label>
                                        <input type="color" class="form-control rounded-2 color-picker"
                                            id="header_background_color" name="header_background_color"
                                            placeholder="{{ __('enter_header_background_color') }}"
                                            value="{{ old('header_background_color', $row->header_background_color) }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="text_color" class="form-label">{{ __('text_color') }}</label>
                                        <input type="color" class="form-control rounded-2 color-picker" id="text_color"
                                            name="text_color" placeholder="{{ __('enter_text_color') }}"
                                            value="{{ old('text_color', $row->text_color) }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="label_color" class="form-label">{{ __('label_color') }}</label>
                                        <input type="color" class="form-control rounded-2 color-picker"
                                            id="label_color" name="label_color"
                                            placeholder="{{ __('enter_label_color') }}"
                                            value="{{ old('label_color', $row->label_color) }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="name_color" class="form-label">{{ __('name_color') }}</label>
                                        <input type="color" class="form-control rounded-2 color-picker" id="name_color"
                                            name="name_color" placeholder="{{ __('enter_name_color') }}"
                                            value="{{ old('name_color', $row->name_color) }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="availability_color"
                                            class="form-label">{{ __('availability_color') }}</label>
                                        <input type="color" class="form-control rounded-2 color-picker" id="availability_color"
                                            name="availability_color" placeholder="{{ __('enter_availability_color') }}"
                                            value="{{ old('availability_color', $row->availability_color) }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="font_size" class="form-label">{{ __('body_font_size') }}</label>
                                        <input type="number" class="form-control rounded-2" id="font_size"
                                            name="font_size" placeholder="{{ __('enter_font_size') }}"
                                            value="{{ old('font_size', $row->font_size) }}">
                                            <span>{{ __('in_pixels') }}</span>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="icon_size" class="form-label">{{ __('button_icon_size') }}</label>
                                        <input type="number" class="form-control rounded-2" id="icon_size"
                                            name="icon_size" placeholder="{{ __('enter_icon_size') }}"
                                            value="{{ old('icon_size', $row->icon_size) }}">
                                            <span>{{ __('in_pixels') }}</span>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-4">
                                        <label for="font_family" class="form-label">
                                            {{ __('font_family') }}
                                        </label>
                                        <select id="font_family" name="font_family"
                                            class="multiple-select-1 js-example-basic-multiple form-select-lg rounded-0 mb-3"
                                            aria-label=".form-select-lg example">
                                            @foreach (config('static_array.font_family') as $key => $font)
                                                <option value="{{ $key }}"
                                                    @if (is_array($row->font_family)) {{ in_array($key, $row->font_family) ? 'selected' : '' }} @endif
                                                    >
                                                    {{ $font }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end align-items-center mt-30">
                                            <button id="" class="btn btn-primary d-none preloader"
                                                type="button" disabled>
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Loading...
                                            </button>
                                            <button type="submit"
                                                class="btn btn-primary save">{{ __('submit') }}</button>
                                                <a href="javascript:void(0)" class="btn btn-primary __js_reset_settings mx-2" title="{{ __('reset_settings') }}" data-url="{{ route('client.chatwidget.reset-setting', $row->id) }}">
                                                    <i class="las la-sync"></i> {{ __('reset') }}
                                                </a>                                            
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('addon:ChatWidget::partials.__create_contact')
    @include('addon:ChatWidget::partials.__edit_contact')
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
    <script src="{{ static_asset('admin/js/jquery.drawrpalette-min.js') }}"></script>
    <script src="{{ static_asset('admin/js/chatwidget.js') }}"></script>
    <script>
        const url = "{{ route('client.chatwidget.contact.sort') }}";
        $(document).ready(function() {
            $('body').on('click', '.color-picker', function(event) {
                event.preventDefault();
            });
            $('.color-picker').each(function() {
                var $input = $(this);
                $(this).drawrpalette({
                    eventName: 'click',
                    paletteWidth: 200,
                    paletteHeight: 200,
                    onChange: function(color) {
                    }
                });
            });

            $('#enable_box').change(function() {
                var enableBoxValue = $(this).val();
                if (enableBoxValue === '0') {
                    $('#phoneInputWrapper').show();
                } else {
                    $('#phoneInputWrapper').hide();
                }
            });
        });
    </script>
@endpush
