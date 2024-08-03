<div class="contactManage__tabs">
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                type="button" role="tab" aria-controls="pills-profile" aria-selected="true">profile</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-flow"
                type="button" role="tab" aria-controls="pills-flow" aria-selected="false">Input flow</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-fields-tab" data-bs-toggle="pill" data-bs-target="#pills-fields"
                type="button" role="tab" aria-controls="pills-fields" aria-selected="false">custom fields</button>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="contactManage__body">
                <div class="contactManage__left">
                    <div class="avatar">
                        <img src="{{ getFileLink('40x40', $contact->images) }}" alt="{{ $contact->name }}">
                    </div>
                    <div class="avatar__inform">
                        <ul>
                            <li>
                                <i class="las la-robot"></i>
                                <span>{{ $contact->bot_reply ? __('bot_reply_on') : __('bot_reply_off') }}</span>
                            </li>
                            <li>
                                <i class="las la-id-card"></i>
                                <span>{{ __('phone') }}: {{ $contact->phone }}</span>
                            </li>
                            @if ($contact->email)
                                <li>
                                    <i class="las la-envelope"></i>
                                    <span>{{ __('email') }}: {{ $contact->email }}</span>
                                </li>
                            @endif
                            @if ($contact->address)
                                <li>
                                    <i class="las la-map-marker"></i>
                                    <span>{{ __('address') }}: {{ $contact->address }}</span>
                                </li>
                            @endif
                            @if ($contact->city)
                                <li>
                                    <i class="las la-city"></i>
                                    <span>{{ __('city') }}: {{ $contact->city }}</span>
                                </li>
                            @endif
                            @if ($contact->state)
                                <li>
                                    <i class="las la-location-arrow"></i>
                                    <span>{{ __('state') }}: {{ $contact->state }}</span>
                                </li>
                            @endif
                            @if ($contact->zipcode)
                                <li>
                                    <i class="las la-mail-bulk"></i>
                                    <span>{{ __('zip_code') }}: {{ $contact->zipcode }}</span>
                                </li>
                            @endif
                            <li>
                                <i class="las la-clock"></i>
                                <span>{{ __('created_at') }}:
                                    {{ Carbon\Carbon::parse($contact->created_at)->format('Y-m-d H:i:s') }}</span>
                            </li>
                            @if ($contact->birthdate)
                                <li>
                                    <i class="las la-birthday-cake"></i>
                                    <span>{{ __('birthdate') }}: {{ $contact->birthdate }}</span>
                                </li>
                            @endif
                            @if ($contact->last_conversation_at)
                                <li>
                                    <i class="las la-sms"></i>
                                    <span>{{ __('last_conversation') }}:
                                        {{ Carbon\Carbon::parse($contact->last_conversation_at)->diffForHumans() }}</span>
                                </li>
                            @endif
                            @if ($contact->gender)
                                <li>
                                    <i class="las la-venus-mars"></i>
                                    <span>{{ __('gender') }}: {{ $contact->gender }}</span>
                                </li>
                            @endif
                            @if ($contact->occupation)
                                <li>
                                    <i class="las la-briefcase"></i>
                                    <span>{{ __('occupation') }}: {{ $contact->occupation }}</span>
                                </li>
                            @endif
                            <li>
                                @if ($contact->is_verified)
                                    <i class="las la-check-circle text-success"></i>
                                    <span>{{ __('verified') }}</span>
                                @else
                                    <i class="las la-times-circle text-danger"></i>
                                    <span>{{ __('not_verified') }}</span>
                                @endif
                            </li>
                            @if ($contact->is_blacklist == '1')
                                <li>
                                    <i class="las la-ban text-danger"></i>
                                    <span>{{ __('blacklisted') }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="contactManage__right">
                    <div class="contactManage__content">
                        <h5 class="content__title">{{ $contact->name }}</h5>
                        <form action="{{ route('client.contact.update', $contact->id) }}" method="post"
                            enctype="multipart/form-data" id="" class="form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <div class="select-type-v2">
                                            <label for="assignee_id" class="form-label">{{ __('assignee_id') }}</label>
                                            <select id="assignee_id" name="assignee_id[]" class="form-select rounded-0"
                                                aria-label=".form-select-lg example">
                                                <option value="">{{ __('select') }}</option>
                                                @foreach ($staffs as $key => $staf)
                                                    <option value="{{ $staf->id }}"
                                                        {{ old('assignee_id', $contact->assignee_id) ? 'selected' : '' }}>
                                                        {{ $staf->user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="nk-block-des text-danger">
                                                <p class="error">{{ $errors->first('assignee_id') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <div class="select-type-v2">
                                            <label for="contact_list_id"
                                                class="form-label">{{ __('contact_lists') }}</label>
                                            <select id="contact_list_id" name="contact_list_id[]"
                                                class="form-select rounded-0" aria-label=".form-select-lg example"
                                                multiple="multiple">
                                                @foreach ($lists as $key => $list)
                                                    <option value="{{ $key }}"
                                                        {{ in_array($key, $contact->contactList->pluck('contact_list_id')->toArray()) ? 'selected' : '' }}>
                                                        {{ $list }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="nk-block-des text-danger">
                                                <p class="error">{{ $errors->first('contact_list_id') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <div class="select-type-v2">
                                            <label for="segment_id" class="form-label">{{ __('segments') }}</label>
                                            <select id="segment_id" name="segment_id[]" class="form-select rounded-0"
                                                aria-label=".form-select-lg example" multiple="multiple">
                                                @foreach ($segments as $key => $segment)
                                                    <option value="{{ $key }}"
                                                        {{ in_array($key, $contact->segmentList->pluck('segment_id')->toArray()) ? 'selected' : '' }}>
                                                        {{ $segment }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="nk-block-des text-danger">
                                                <p class="error">{{ $errors->first('segment_id') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="occupation" class="form-label">{{ __('occupation') }}</label>
                                        <input type="text" value="{{ old('occupation', $contact->name) }}"
                                            class="form-control rounded-2" id="occupation" name="occupation"
                                            placeholder="{{ __('occupation') }}">
                                        @if ($errors->has('occupation'))
                                            <div class="nk-block-des text-danger">
                                                <p>{{ $errors->first('occupation') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="address" class="form-label">{{ __('address') }}</label>
                                        <input type="text" value="{{ old('address', $contact->address) }}"
                                            class="form-control rounded-2" id="address" name="address"
                                            placeholder="{{ __('address') }}">
                                        @if ($errors->has('address'))
                                            <div class="nk-block-des text-danger">
                                                <p>{{ $errors->first('address') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="birthdate" class="form-label">{{ __('birthdate') }}</label>
                                        <input type="text" value="{{ old('birthdate', $contact->birthdate) }}"
                                            class="form-control rounded-2" id="birthdate" name="birthdate"
                                            placeholder="{{ __('birthdate') }}">
                                        @if ($errors->has('birthdate'))
                                            <div class="nk-block-des text-danger">
                                                <p>{{ $errors->first('birthdate') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="email" class="form-label">{{ __('email') }}</label>
                                        <input type="email" value="{{ old('email', $contact->email) }}"
                                            class="form-control rounded-2" id="email" name="email"
                                            placeholder="{{ __('email') }}">
                                        @if ($errors->has('email'))
                                            <div class="nk-block-des text-danger">
                                                <p>{{ $errors->first('email') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="country_id" class="form-label">{{ __('country') }}</label>
                                        <select class="form-select rounded-0" aria-label=".form-select-lg example"
                                            id="country_id" name="country_id" style="width: 100%">
                                            <option value="" selected>{{ __('select_country') }}</option>
                                            @foreach ($countries as $key => $country)
                                                <option value="{{ $key }}"
                                                    {{ old('country_id', $contact->country_id) == $key ? 'selected' : '' }}>
                                                    {{ __($country) }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('country_id'))
                                            <div class="nk-block-des text-danger">
                                                <p>{{ $errors->first('country_id') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end align-items-center mt-30">
                                <button type="submit" class="btn sg-btn-primary">{{ __('update') }}</button>
                                @include('backend.common.loading-btn', [
                                    'class' => 'btn sg-btn-primary',
                                ])
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-flow" role="tabpanel" aria-labelledby="pills-flow-tab">
            @if ($contact->contact_flow && $contact->contact_flow->flow)
                {{ $contact->contact_flow->flow->name }}
            @else
                {{ __('flow_not_assigned') }}
            @endif
        </div>
        <div class="tab-pane fade" id="pills-fields" role="tabpanel" aria-labelledby="pills-fields-tab">
            <!-- Block Contact -->
            <div class="block-contact mb-3">
                <button type="button" class="btn btn-danger" onclick="confirmBlockContact({{ $contact->id }})">
                    <i class="las la-ban text-white"></i> {{ __('block_contact') }}
                </button>
                <!-- Use a modal or confirmation dialog for block action -->
            </div>

            <!-- Remove Bot Auto-reply -->
            <div class="remove-auto-reply mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remove_auto_reply"
                        {{ $contact->bot_reply ? 'checked' : '' }}>
                    <label class="form-check-label" for="remove_auto_reply">
                        {{ __('remove_bot_auto_reply') }}
                    </label>
                </div>
                <!-- Use JavaScript to handle toggle functionality -->
            </div>
        </div>
    </div>
</div>
