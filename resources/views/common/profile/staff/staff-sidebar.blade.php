
 <div class="col-xxl-3 col-lg-4 col-md-4">
    <div class="bg-white redious-border py-3 py-sm-30 mb-30">
        <div class="email-tamplate-sidenav">
            <div>
                <div class="user-info-panel align-items-center justify-content-center mb-3">
                    <div class="profile-img align-items-center justify-content-center d-flex mb-2">
                        <img src="{{ optional(\Sentinel::getUser()->image)->image_small_two ? asset(optional(\Sentinel::getUser()->image)->image_small_two) : getFileLink('80X80', []) }}">
                    </div>
                    <div class="user-info d-flex justify-content-center align-items-center">
                        <div>
                            <h4 class="text-center">{{ \Sentinel::getUser()->first_name . ' ' . \Sentinel::getUser()->last_name }}</h4>
                            <span class="text-center">{{ \Sentinel::getUser()->email }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="default-sidenav">
                <li>
                    <a href="{{route('staff.profile')}}" class="{{ request()->routeIs('staff.profile') ? 'active' : '' }}">
                        <span class="icon"><i class="las la-feather"></i></span>
                        <span>{{ __('personal_information') }}</span>
                    </a>
                </li>

                @if(!blank(Sentinel::getUser()->accounts(Sentinel::getUser()->id)))
                    <li>
                        <a href="{{route('user.accounts')}}" class="{{ request()->routeIs('user.accounts') ? 'active' : '' }}">
                            <span class="icon"><i class="las la-heading"></i></span>
                            <span>{{ __('accounts') }}</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{route('staff.payment.logs')}}" class="{{ request()->routeIs('staff.payment.logs') ? 'active' : '' }}">
                        <span class="icon"><i class="las la-bullhorn"></i></span>
                        <span>{{ __('transaction_log') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('staff.account-activity')}}" class="{{ request()->routeIs('staff.account-activity') ? 'active' : '' }}">
                        <span class="icon"><i class="las la-memory"></i></span>
                        <span>{{ __('login_activity') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('staff.security-settings')}}" class="{{ request()->routeIs('staff.security-settings') ? 'active' : '' }}">
                        <span class="icon"><i class="las la-palette"></i></span>
                        <span>{{ __('change_password') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>


 {{-- Update Profile modal --}}
<div class="modal fade" tabindex="-1" id="update-profile">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('update_profile')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-lg">
                <form action="{{route('staff.update.profile')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" id="update-profile-form">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="first_name">{{ __('first_name')  }}</label>
                        <input type="text" hidden name="id" id="id" value="{{ \Sentinel::getUser()->id }}">
                        <input type="text" name="first_name" class="form-control" id="first_name" value="{{ \Sentinel::getUser()->first_name }}" placeholder="{{__('first_name')}}" required>
                        @if($errors->has('first_name'))
                        <div class="invalid-feedback help-block">
                            <p>{{ $errors->first('first_name') }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="last_name">{{__('last_name')}}</label>
                        <input type="text" name="last_name" class="form-control" value="{{ \Sentinel::getUser()->last_name }}" id="last_name" placeholder="{{__('last_name')}}" required>
                        @if($errors->has('last_name'))
                            <div class="invalid-feedback help-block">
                                <p>{{ $errors->first('last_name') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email">{{__('email')}}</label>
                        <input type="email" name="email" class="form-control" value="{{ \Sentinel::getUser()->email }}" id="last_name" placeholder="{{__('email')}}" required>
                        @if($errors->has('email'))
                            <div class="invalid-feedback help-block">
                                <p>{{ $errors->first('email') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3 input_file_div">
                        <div class="mb-3 mt-2">
                            <label class="form-label mb-1">{{ __('profile') }}</label>
                            <input class="form-control sp_file_input file_picker" type="file" id="profilePhoto"
                                name="image" accept="image/*">
                                @if ($errors->has('image'))
                                <div class="invalid-feedback help-block">
                                    <p>{{ $errors->first('image') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="selected-files d-flex flex-wrap gap-20">
                            <div class="selected-files-item">
                                <img class="selected-img"
                                 src="{{ optional(Sentinel::getUser()->image)->original_image ? asset(optional(Sentinel::getUser()->image)->original_image) : getFileLink('80X80', []) }}"
                                 alt="favicon">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3 text-right mt-3">
                            <button type="submit" class="btn sg-btn-primary">{{__('update')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('common.script')
